window.onload = function () {
  // リンクを新規タブで開く
  let aTags = document.getElementsByTagName('a');
  //for( let i = 0; i < aTags.length; i++  ){
  for( let i in aTags ){
    aTags[i].target = '_blank';
  }
}

