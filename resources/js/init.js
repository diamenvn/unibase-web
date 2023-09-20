// img = '';
// $(function(){
//   $.ajaxSetup({
//     headers: {
//       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
//   });

//   $('*[timepicker="true"]').flatpickr({
//     enableTime: true,
//     dateFormat: "Y-m-d H:i",
//   });

//   $('#singlebutton').click(function(){
//     time = $('#time').val();
//     data = $('#comment').val();
//     id = gup('id');

//     if (!!!time){
//       alert('Time require!!');
//       return
//     }

//     if(!!!data){
//       alert('Data comment require');
//       return
//     }

//     $.ajax({
//       type:'POST',
//       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//       url:'/facebook/comments/add',
//       data:{
//         time: time,
//         data: data,
//         img: img,
//         id: gup('id')
//       },
//       success:function(data){
//         alert(data.msg);
//       }
//     });
//   });

//   $('#frmUploadImage').on('submit',(function(e) {
//     UploadImageAPI(e, new FormData(this));
//   }));

//   $('input[type="file"]').change(function(){
//     $('#frmUploadImage').submit();
//   });

// });

// function UploadImageAPI(e, formData){
//   e.preventDefault();
//   $.ajax('/api/uploadimage', {
//       headers: {
//           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//       },
//       type: 'POST',
//       data: formData,
//       processData: false,
//       contentType: false,
//       success: function(result){
//         img = result;
//         $('#previewImg').attr('src', result);
//       }
//   });
// }

// function gup( name, url ) {
//   if (!url) url = location.href;
//   name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
//   var regexS = "[\\?&]"+name+"=([^&#]*)";
//   var regex = new RegExp( regexS );
//   var results = regex.exec( url );
//   return results == null ? null : results[1];
// }
