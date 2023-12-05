isDisabled = (element) => {
    if (element.attr("v-disabled") == "") return true;
    return false;
}