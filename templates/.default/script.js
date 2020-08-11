$(document).ready(function(){
  $('.reestr__item-content-js').on('click', function(e){
    $(e.currentTarget).siblings().toggle();
  })
})