var site = 'http://tz1';
var back = site + '/back/';
var saved = 0;
var page = 1;

//работа с base64
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    //метод для кодировки в base64 на javascript 
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                    this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                    this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },

    //метод для раскодировки из base64 
    decode: function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    // метод для кодировки в utf8 
    _utf8_encode: function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;

    },

    //метод для раскодировки из urf8 
    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}

//получаем из адреса параметр по имени
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

//get list links
function getList(page) {
    $.ajax({
        url: back + 'actions.php',
        method: 'post',
        dataType: "json",
        data: {p: page, act: 1}
    }).done(function (data) {
        
        if (data.error == 1) {
            return;
        }
        
        renderTable(data);
    }).fail(function (xhr, status, errorThrown) {
        console.log("Error: " + errorThrown);
        console.log("Status: " + status);
        console.dir(xhr);
    });
}

//render table
function renderTable(data) {
    let items = data.table;
    
    if (items.count < 1) {
        return;
    }
    
    $('#linktable').html('');
    $('#DownPagins').html('');
    $('#UpPagins').html('');
    
    for (k in items) {
        let item = items[k];
        let c = ++k;
        let row = '<tr><th scope="row">' + c + '</th>';
        row += '<td><a href="' + item.longurl + '" target="_blank">' + item.longurl + '</a></td>';
        row += '<td><a href="' + site + '/' + item.shorturl + '" target="_blank">' + item.shorturl + '</a></td>';
        row += '<td>' + item.date_create + '</td>';
        row += '<td>' + item.counters + '</td>';
        row += '<td><button onclick=dellink("' + item.id + '") class="btn btn-small pmd-btn-fab pmd-ripple-effect btn-danger">';
        row += '<i class="fas fa-trash"></i></button></td></tr>';
        $('#linktable').append(row);
    }
    
    let offset = data.offset;
    let all = data.all;
    renderPagins(offset, all);
}

//целочисленное деление
function div(val, by) {
    return (val - val % by) / by;
}

//рисуем пагинацию
function renderPagins(offset, all) {
    if ((offset < 1) || (page < 1) || (offset >= all) || (all < 1)) {
        return;
    }
    
    var c = div(all, offset);
    let ostatok = all % offset;
    c = (ostatok > 0) ? c = c + 1 : c;
    
    if (c < 2) {
        return;
    }

    let pagePrev = parseInt(page) - 1;
    let prevLink = site + '/?p=' + pagePrev;
    if (page > c) {
        location.href = prevLink;
    }

    var prev = (pagePrev > 0) ? prev = '<li class="page-item"><a class="page-link" href="' + prevLink + '">&nbsp;<i class="fas fa-angle-left"></i></a></li>' : '';
    let pageNext = parseInt(page) + 1;
    let nextLink = site + '/?p=' + pageNext;
    var next = (pageNext <= c) ? next = '<li class="page-item"><a class="page-link" href="' + nextLink + '">&nbsp;<i class="fas fa-angle-right"></i></a></li>' : '';
    var listing = '';
    var i = 1;
    
    while (i <= c) {
        pp = i;
        var pageLink = site + '/?p=' + pp;
        var active = (i == page) ? active = 'page-item active" aria-current="page"' : 'page-item';
        listing += '<li class="' + active + '"><a class="page-link" href="' + pageLink + '">' + pp + '</a></li>'
        i++;
    }

    $('#DownPagins').html(prev + listing + next);
    $('#UpPagins').html(prev + listing + next);


}

//удаляем линк
function dellink(ids)
{
    $.ajax({
        url: back + 'actions.php',
        method: 'post',
        dataType: "text",
        data: {id: ids, act: 3}
    }).done(function (data) {
        $('#delToast').html('<strong>Удалено</strong>');
        $('#delToast').show();
        
        setTimeout(function () {
            $('#delToast').hide();
        }, 1000);
        
        getList(page);
    }).fail(function (xhr, status, errorThrown) {
        console.log("Error: " + errorThrown);
        console.log("Status: " + status);
        console.dir(xhr);
    });
}

//генерация и сохранение
$('#savebtn').on('click', function () {
    var inputValue = $('#longlink').val().trim();
    
    if (inputValue) {
        $('#shotlink').val('');
        $.ajax({
            url: back + 'actions.php',
            method: 'post',
            dataType: "json",
            data: {u: Base64.encode(inputValue), act: 2}
        }).done(function (data) {
            
            if (data.error == 1) {
                return;
            }
            
            if (data.error == 2) {
                var shortcode = (data.shortcode !== false) ? shortcode = data.shortcode.trim() : '';
                $('#shotlink').val(shortcode);
                return;
            }
            
            var shortcode = (data.shortcode !== false) ? shortcode = data.shortcode.trim() : '';
            $('#shotlink').val(shortcode);
            $('#textToast').html('<strong>Сохранено</strong>');
            $('#textToast').show();
            
            setTimeout(function () {
                $('#textToast').hide();
            }, 1000);
            
            saved = 1;
        }).fail(function (xhr, status, errorThrown) {
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    }

});

$('#shortbtn').on('click', function () {
    //копируем через select поля
    var inputValue = $('#shotlink').val().trim();
    
    if (inputValue) {
        var fullLink = site + '/' + inputValue;
        $('#shotlink').val(fullLink);
        $('#shotlink').select();
        document.execCommand('copy');
        const sel = window.getSelection();
        sel.removeAllRanges();
        $('#shotlink').val(inputValue);
        $('#textToast').html('<strong>Скопировано</strong>');
        $('#textToast').show();
        
        setTimeout(function () {
            $('#textToast').hide();
        }, 1000);        
    }
});

$('#addLinkModal').on('hidden.bs.modal', function (e) {
    //если что-то сохранли обновлем список
    if (saved === 1) {
        getList(page);
    }
});

$('#addLinkModal').on('show.bs.modal', function (e) {
    //при открытии чистим поля
    $('#longlink').val('');
    $('#shotlink').val('');
});

$('#seabtn').on('click', function () {
    //поиск по shortcode
    var inputValue = $('#sea').val().trim();
    
    if (inputValue) {
        $.ajax({
            url: back + 'actions.php',
            method: 'post',
            dataType: "json",
            data: {s: Base64.encode(inputValue), act: 4}
        }).done(function (data) {
            
            if (data.error == 1) {
                return;
            }
            
            if (data.table.count < 1) {
                return;
            }
            
            renderTable(data);
        }).fail(function (xhr, status, errorThrown) {
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    } else {
        getList(page);
    }
});


$(function () {
    //page
    $('#textToast').hide();
    $('#delToast').hide();
    page = getUrlParameter('p');
    page = (isNaN(page)) ? page = 1 : page;
    page = (String(page).trim().length < 1) ? page = 1 : page;
    getList(page);
});