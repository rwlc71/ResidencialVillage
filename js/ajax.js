/*! jQuery v1.8.3 jquery.com | jquery.org/license */

function ajax(options, callback) {
    
    var XHR = new XMLHttpRequest();
    if (callback !== undefined) {
        XHR.onload = function() {
            callback(XHR.responseText);
        };
    }
    if (options.method === undefined) {
        method = 'GET';
    }

    if (options.method == 'GET' && options.data !== null) {
        if(options.data !== undefined) {
            options.data = encodeValues(options.data);
        }
    }

    var lowerHeaders = {};
    for (var k in options.headers) {
        if (options.headers.hasOwnProperty(k)) {
            lowerHeaders[k.toLowerCase()] = options.headers[k];
        }
    }

    options.headers = lowerHeaders;
    if (options.headers['content-type'] === undefined && options.method !== 'GET'  && options.method !== 'DELETE') {
        options.headers['content-type'] = 'application/json; charset=utf8';
    } else if (options.headers['content-type'].indexOf('application/x-www-form-urlencoded') !== -1) {
        options.method = 'POST';
        if(options.data !== undefined) {
            options.data = encodeValues(options.data);
        }
    }

    if (options.headers['content-type'] !== undefined && options.headers['content-type'].indexOf('application/json') !== -1) {
        if (typeof options.data == 'object') {
            options.data = JSON.stringify(options.data);
        }        
    }

    XHR.open(options.method, options.url, true);
    for (k in options.headers) {
        if (options.headers.hasOwnProperty(k)) {
            var partesK = k.split('-');
            for (var i = 0; i < partesK.length; i++) {
                partesK[i] = partesK[i].charAt(0).toUpperCase() + partesK[i].slice(1);
            }
            XHR.setRequestHeader(partesK.join('-'), options.headers[k]);
        }
    }
    XHR.send(options.data);
}