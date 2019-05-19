var lastImageId = 0;
var $grid = null;
var masonry = null;
var imageLoader = null;

window.onload = function() {
    console.log('window.onload');
    $grid = $('#collage-view-images');
    imageLoader = setInterval(loadNewImages, 5000);
    loadNewImages();
};

function loadNewImages() {
    var url = '/collages/' + COLLAGE_ID + '/ui-images/after-image-id/' + lastImageId;
    console.log('Load new images: '+url);
    $.getJSON(url, function (images) {
        console.log(images);
        images.forEach(function (image) {
            if($('.collage-image-'+image.id).length) {
                return;
            }

            var $elem = $(getImageHtml(image));
            $grid.prepend($elem);

            if(!masonry) {
                console.log('boot masonry with img id '+image.id);
                masonry = new Masonry('#collage-view-images', {itemSelector: '.collage-image', columnWidth: 1});
            } else {
                console.log('prepend img id '+image.id);
                masonry.prepended($elem[0]);
            }

            lastImageId = image.id;
        });
    });
}

function getImageHtml(image) {
    return '' +
        '<div ' +
        '   class="collage-image collage-image-' + image.type + ' collage-image-' + image.id + '" ' +
        '   style="background-image: url(' + image.link + ')">' +
        '</div>';
}
