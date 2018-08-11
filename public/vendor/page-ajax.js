'use strict';

(function($) {
 $('#page_load_more').on('click', function(){
    var _this = $(this);
    _this.attr('disabled', 'disabled')
    var page = _this.data('page');
    page++;
    $.ajax({
      'url' : '/page-api',
      'dataType' : 'json',
      'data' : {
        'page' : page
      }
    }).done(function(response){
      if(response.empty == 1){
        _this.remove();
        return;
      }
      var pageListNavigation = $('#page_list_navigation');

      for(var i = 0; i < response.result.length; i++){
        pageListNavigation.append("<div class=\"list-item\">"+response.result[i]+"</div>");
      }
      _this.data('page', page);
    }).always(function(){
      _this.removeAttr('disabled')
    });
 })
})(jQuery);