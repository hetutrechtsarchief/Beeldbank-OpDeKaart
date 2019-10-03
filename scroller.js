function initScroller() {
  var reachedTheEnd = false;

  $('.container').empty();
  $('.container').infiniteScroll('destroy');
  $('.einde').hide();

  var $container = $('.container').infiniteScroll({
    path: function() {
      // console.log("this: ",this);
      // console.log("path function", this.pageIndex);
      return window.apiBase + window.wikidataID+'&page=' + this.pageIndex;
    },
    // load response as flat text
    responseType: 'text',
    status: '.page-load-status',
    history: false,
  });
  //init


  $container.infiniteScroll('loadNextPage');

  $container.on('load.infiniteScroll', function(event, response) {
    var data = JSON.parse(response);

    if (data && data.length==0) {
      console.log("done..")
      $('.container').infiniteScroll('destroy'); 
      $('.einde').fadeIn();
      return;
    }

    data = data.filter(function (obj) {
      reachedTheEnd = ($("#"+obj.guid).length>0);
      return !reachedTheEnd; //don't add if already exists
    });

    var itemsHTML = data.map(getItemHTML).join('');
    // convert HTML string into elements
    var $items =  $(itemsHTML);
    // append item elements
    
    $items.find(".photo-item__image").on("load", function() {
      $(this).hide();
      $(this).fadeIn();
    });

    $items.find(".photo-item__image").on("error", function() {
      console.log("image load error",$(this).attr("src"));

      if ($("#chkOnlineOnly").prop('checked')) {
        $(this).hide();
      } else {
        $(this).attr("src","https://hetutrechtsarchief.nl/templates/cust-utr-website/img/mediabank-noimage.png");
      }

    });

    $container.infiniteScroll('appendItems', $items);
  });
}

//------------------//

var itemTemplateSrc = $('#photo-item-template').html();

$( document ).tooltip({
  track: true
});

function getItemHTML(itemData) {
  return microTemplate(itemTemplateSrc, itemData);
}

// micro templating, sort-of
function microTemplate( src, data ) {
  if (!src) {
    console.log("warning: microTemplate src undefined");
    return;
  }

  // replace {{tags}} in source
  return src.replace( /\{\{([\w\-_\.]+)\}\}/gi, function( match, key ) {
    // walk through objects to get value
    var value = data;
    key.split('.').forEach( function( part ) {
      value = quoteattr(value[part]); //.replace(/\"/g, "");
    });
    return value;
  });
}

function quoteattr(s, preserveCR) {
  preserveCR = preserveCR ? '&#13;' : '\n';
  return ('' + s) /* Forces the conversion to string. */
    .replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
    .replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
    .replace(/"/g, '&quot;')
    .replace(/<ZR>/g, ' ') /// 
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    
    /*
    You may add other replacements here for HTML only 
    (but it's not necessary).
    Or for XML, only if the named entities are defined in its DTD.
    */ 
    .replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
    .replace(/[\r\n]/g, preserveCR);
    ;
}
