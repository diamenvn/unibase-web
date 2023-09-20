<?php
namespace App\Services;

use GuzzleHttp\Client;
use Config;
use App\Model\Mysql\PageModel;
use App\Model\Mysql\CommentModel;
use Carbon\Carbon;

class PageService
{
  // private $page;
  public function __construct(PageModel $page, CommentModel $comment)
  {
    $this->page = $page;
    $this->comment = $comment;
    $this->client = new Client([
      'base_uri' => Config::get('services.facebook.facebook_api')
    ]);
  }

  public function getAllPage($userToken) {
    $uri = 'me/accounts?access_token=' . $userToken;
    $res = $this->client->get($uri)->getBody()->getContents();
    $data = json_decode($res, true);
    $page = array();
    foreach ($data['data'] as $key => $value) {
      $res = $this->detailPage($value['access_token']);
      array_push($page, $res);
    }
    return $page;
  }

  public function getPageConnected($user){
    $query = $this->page->where('uid', $user->uid)->orderBy('id', 'DESC')->get();
    $page = array();
    if (!empty($query)) {
      foreach ($query as $key => $value) {
        $res = $this->detailPage($value->token);
        array_push($page, $res);
      }
    }
    return $page;
  }

  public function getFeed($pageToken){
    $uri = 'me/feed?access_token=' . $pageToken;
    $res = $this->client->get($uri)->getBody()->getContents();
    $data = json_decode($res, true);
    return $data['data'];
  }

  public function detailPage($pageToken){
    $field = array(
      'id',
      'name',
      'access_token',
      'cover.type(large)',
      'picture.type(large)',
    );

    $field = implode(",", $field); // Array to string fields
    $uri = 'me?fields=' . $field . '&access_token=' . $pageToken;
    $res = $this->client->get($uri)->getBody()->getContents();
    $data = json_decode($res, true);
    return $data;
  }

  public function connect($user, $page){
    $res = $this->page->updateOrCreate(
      [
        'uid' => $user->uid,
        'pid' => $page['id']
      ],
      [
        'name' => $page['name'],
        'token' => $page['access_token'],
        'status'  =>  1 // 1 is connect
      ]
    );
    return $res;
  }

  public function addComment($user, $data){
    $comment = $this->comment;
    $comment->uid = $user->uid;
    $comment->pid = $data['pid'];
    $comment->fid = $data['fid'];
    $comment->data = $data['data'];
    $comment->time = $data['time'];
    $comment->attachment = $data['attachment'];
    $comment->save();
    return $comment;
  }

  public function postCommentToFacebook(){

    $start = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:00'))->setTimezone('Asia/Ho_Chi_Minh')->toDateTimeString();
    $end = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:59'))->setTimezone('Asia/Ho_Chi_Minh')->toDateTimeString();

    $query = $this->comment->where('time', '>=', $start)->where('time', '<=', $end)->get();
    foreach ($query as $key => $value) {
      $data['message'] = $value->data;
      $data['attachment_url'] = $value->attachment;
      $pageToken = $this->page->where('pid', $value->pid)->where('uid', $value->uid)->first()->token;
      $uri = $value->fid . '/comments?access_token=' . $pageToken;
      $res = $this->client->request('POST', $uri, ['form_params' => $data]);
    }
  }
}


?>
