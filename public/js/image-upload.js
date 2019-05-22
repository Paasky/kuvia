window.onload = function() {
    $('#image-upload-preview').click(function(){ $('input#image[type="file"]').click() });
    $("#image").change(function() {
        $('form input[type="submit"]').attr('disabled', true);
        readURL(this);
    });
};

function readURL(input) {

    if (input.files && input.files[0]) {
        getOrientation(input.files[0], function(orientation){
            console.log('got file orientation');
            console.log(orientation);

            if(orientation < 1){
                orientation = 1;
            }

            var reader = new FileReader();

            reader.onload = function(e) {
                var orientations = {
                    1: 0,
                    2: 0,
                    3: 180,
                    4: 180,
                    5: 90,
                    6: 90,
                    7: 270,
                    8: 270,
                };

                $('#image-upload-preview')
                    .css('background-image', 'url('+ e.target.result +')')
                    .css('transform', 'rotate('+orientations[orientation]+'deg)');
                setTimeout(function(){
                    $('form input[type="submit"]').removeAttr('disabled');
                }, 1000);
            };

            reader.readAsDataURL(input.files[0]);
        });
    } else {
        $('form input[type="submit"]').disable();
    }
}


function getOrientation(file, callback) {
    var reader = new FileReader();
    reader.onload = function(e) {

        var view = new DataView(e.target.result);
        if (view.getUint16(0, false) != 0xFFD8)
        {
            return callback(-2);
        }
        var length = view.byteLength, offset = 2;
        while (offset < length)
        {
            if (view.getUint16(offset+2, false) <= 8) return callback(-1);
            var marker = view.getUint16(offset, false);
            offset += 2;
            if (marker == 0xFFE1)
            {
                if (view.getUint32(offset += 2, false) != 0x45786966)
                {
                    return callback(-1);
                }

                var little = view.getUint16(offset += 6, false) == 0x4949;
                offset += view.getUint32(offset + 4, little);
                var tags = view.getUint16(offset, little);
                offset += 2;
                for (var i = 0; i < tags; i++)
                {
                    if (view.getUint16(offset + (i * 12), little) == 0x0112)
                    {
                        return callback(view.getUint16(offset + (i * 12) + 8, little));
                    }
                }
            }
            else if ((marker & 0xFF00) != 0xFF00)
            {
                break;
            }
            else
            {
                offset += view.getUint16(offset, false);
            }
        }
        return callback(-1);
    };
    reader.readAsArrayBuffer(file);
}

// usage:
// var input = document.getElementById('input');
// input.onchange = function(e) {
//     getOrientation(input.files[0], function(orientation) {
//         alert('orientation: ' + orientation);
//     });
// }
