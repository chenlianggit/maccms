;(function() {
    //JSON解析包   
    // Create a JSON object only if one does not already exist. We create the
    // methods in a closure to avoid creating global variables.
    var JSON;
    if (!JSON) {
        JSON = {};
    }

    (function () {
        "use strict";

        function f(n) {
            // Format integers to have at least two digits.
            return n < 10 ? '0' + n : n;
        }

        if (typeof Date.prototype.toJSON !== 'function') {

            Date.prototype.toJSON = function (key) {

                return isFinite(this.valueOf()) ?
                    this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z' : null;
            };

            String.prototype.toJSON      =
                Number.prototype.toJSON  =
                Boolean.prototype.toJSON = function (key) {
                    return this.valueOf();
                };
        }

        var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
            escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
            gap,
            indent,
            meta = {    // table of character substitutions
                '\b': '\\b',
                '\t': '\\t',
                '\n': '\\n',
                '\f': '\\f',
                '\r': '\\r',
                '"' : '\\"',
                '\\': '\\\\'
            },
            rep;


        function quote(string) {



            escapable.lastIndex = 0;
            return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === 'string' ? c :
                    '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' : '"' + string + '"';
        }


        function str(key, holder) {

    // Produce a string from holder[key].

            var i,          // The loop counter.
                k,          // The member key.
                v,          // The member value.
                length,
                mind = gap,
                partial,
                value = holder[key];

    // If the value has a toJSON method, call it to obtain a replacement value.

            if (value && typeof value === 'object' &&
                    typeof value.toJSON === 'function') {
                value = value.toJSON(key);
            }

    // If we were called with a replacer function, then call the replacer to
    // obtain a replacement value.

            if (typeof rep === 'function') {
                value = rep.call(holder, key, value);
            }

    // What happens next depends on the value's type.

            switch (typeof value) {
            case 'string':
                return quote(value);

            case 'number':

    // JSON numbers must be finite. Encode non-finite numbers as null.

                return isFinite(value) ? String(value) : 'null';

            case 'boolean':
            case 'null':


                return String(value);

    // If the type is 'object', we might be dealing with an object or an array or
    // null.

            case 'object':

    // Due to a specification blunder in ECMAScript, typeof null is 'object',
    // so watch out for that case.

                if (!value) {
                    return 'null';
                }

    // Make an array to hold the partial results of stringifying this object value.

                gap += indent;
                partial = [];

    // Is the value an array?

                if (Object.prototype.toString.apply(value) === '[object Array]') {

    // The value is an array. Stringify every element. Use null as a placeholder
    // for non-JSON values.

                    length = value.length;
                    for (i = 0; i < length; i += 1) {
                        partial[i] = str(i, value) || 'null';
                    }

    // Join all of the elements together, separated with commas, and wrap them in
    // brackets.

                    v = partial.length === 0 ? '[]' : gap ?
                        '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' :
                        '[' + partial.join(',') + ']';
                    gap = mind;
                    return v;
                }

    // If the replacer is an array, use it to select the members to be stringified.

                if (rep && typeof rep === 'object') {
                    length = rep.length;
                    for (i = 0; i < length; i += 1) {
                        if (typeof rep[i] === 'string') {
                            k = rep[i];
                            v = str(k, value);
                            if (v) {
                                partial.push(quote(k) + (gap ? ': ' : ':') + v);
                            }
                        }
                    }
                } else {

    // Otherwise, iterate through all of the keys in the object.

                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = str(k, value);
                            if (v) {
                                partial.push(quote(k) + (gap ? ': ' : ':') + v);
                            }
                        }
                    }
                }

    // Join all of the member texts together, separated with commas,
    // and wrap them in braces.

                v = partial.length === 0 ? '{}' : gap ?
                    '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' :
                    '{' + partial.join(',') + '}';
                gap = mind;
                return v;
            }
        }

    // If the JSON object does not yet have a stringify method, give it one.

        if (typeof JSON.stringify !== 'function') {
            JSON.stringify = function (value, replacer, space) {



                var i;
                gap = '';
                indent = '';

    // If the space parameter is a number, make an indent string containing that
    // many spaces.

                if (typeof space === 'number') {
                    for (i = 0; i < space; i += 1) {
                        indent += ' ';
                    }



                } else if (typeof space === 'string') {
                    indent = space;
                }



                rep = replacer;
                if (replacer && typeof replacer !== 'function' &&
                        (typeof replacer !== 'object' ||
                        typeof replacer.length !== 'number')) {
                    throw new Error('JSON.stringify');
                }



                return str('', {'': value});
            };
        }




        if (typeof JSON.parse !== 'function') {
            JSON.parse = function (text, reviver) {



                var j;

                function walk(holder, key) {



                    var k, v, value = holder[key];
                    if (value && typeof value === 'object') {
                        for (k in value) {
                            if (Object.prototype.hasOwnProperty.call(value, k)) {
                                v = walk(value, k);
                                if (v !== undefined) {
                                    value[k] = v;
                                } else {
                                    delete value[k];
                                }
                            }
                        }
                    }
                    return reviver.call(holder, key, value);
                }




                text = String(text);
                cx.lastIndex = 0;
                if (cx.test(text)) {
                    text = text.replace(cx, function (a) {
                        return '\\u' +
                            ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                    });
                }



                if (/^[\],:{}\s]*$/
                        .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                            .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                            .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {



                    j = eval('(' + text + ')');


                    return typeof reviver === 'function' ?
                        walk({'': j}, '') : j;
                }



                throw new SyntaxError('JSON.parse');
            };
        }
    }());
    
    
    //XFLIB开始
    var XFLIB = window.XFLIB = {};
    //消息    
    var Msg_update = '\u60a8\u672a\u5b89\u88c5qq\u65cb\u98ce\u6216\u672a\u6388\u6743\u5b89\u88c5\u6d4f\u89c8\u5668\u63d2\u4ef6\ \n\u70b9\u51fb\u201c\u786e\u5b9a\u201d\uff0c\u4e0b\u8f7d\u5e76\u5b89\u88c5qq\u65cb\u98ce\u53ca\u76f8\u5173\u6d4f\u89c8\u5668\u63d2\u4ef6';
    
    var Msg_not_install = '\u9700\u8981\u5347\u7ea7\u65cb\u98ce\uff0c\u624d\u80fd\u4f7f\u7528\u4e13\u5c5e\u4e0b\u8f7d\ \n\u70b9\u51fb\u201c\u786e\u5b9a\u201d\uff0c\u5f00\u59cb\u5347\u7ea7';
    
    var XF_NPAPI_PLUGIN=null;
    //下载链
    var XF_DOWNLOAD_URL = "http://dldir1.qq.com/invc/cyclone/QQDownload_Setup_46_766_400.exe";
    var MainURI = "http://xf.qq.com/download/lastest";
    //返回码
    var XF_OK = 0;
    var ERR_VERSION_TOO_LOWLY = 1;
    var ERR_NOT_SUPPORT_EXPLORER = 2;
    var ERR_NOT_ENOUGH_PRIVILEGE = 3;
    var ERR_NOT_INSTALL_QQDOWNLOAD = 4;
    //浏览器版本
    var NT_UNKNOWN = -1;
    var NT_IE = 0;
    var NT_FIREFOX = 1;
    var NT_OPERA = 2;
    var NT_CHROME = 3;
    var NT_SAFARI = 4;
    //其他变量
    var g_comFFDownload = null;
    var g_QQDownloadREG = null;
    var g_comIEQQDownload = null;
    var g_libXFMid = null;
    var g_CheckFileType = null;
    var g_SendSingleTask4 = null;
    var g_AddTask3 = null;
    var g_SendMultiTask = null;

    
    //base64编码
    var TextUtil = new Object();
    TextUtil.base64 = function(str)
    {
            var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

            //将Ansi编码的字符串进行Base64编码
            function encode64(input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
            do {
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
            output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2)
            + keyStr.charAt(enc3) + keyStr.charAt(enc4);
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
            return output;
            }
            //将Base64编码字符串转换成Ansi编码的字符串
            function decode64(input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
            if (input.length % 4 != 0) {
            return "";
            }
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
            return "";
            }
            do {
            enc1 = keyStr.indexOf(input.charAt(i++));
            enc2 = keyStr.indexOf(input.charAt(i++));
            enc3 = keyStr.indexOf(input.charAt(i++));
            enc4 = keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
            output += String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
            output += String.fromCharCode(chr3);
            }
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
            return output;
            }
            //utf16to8
            function utf16to8(str) {
            var out, i, len, c;
            out = "";
            len = str.length;
            for(i = 0; i < len; i++) {
            c = str.charCodeAt(i);
            if ((c >= 0x0001) && (c <= 0x007F)) {
            out += str.charAt(i);
            } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
            } else {
            out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
            }
            }
            return out;
            }
            //utf8to16
            function utf8to16(str) {
            var out, i, len, c;
            var char2, char3;
            out = "";
            len = str.length;
            i = 0;
            while(i < len) {
            c = str.charCodeAt(i++);
            switch(c >> 4) {
            case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
            // 0xxxxxxx
            out += str.charAt(i-1);
            break;
            case 12: case 13:
            // 110x xxxx 10xx xxxx
            char2 = str.charCodeAt(i++);
            out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
            break;
            case 14:
            // 1110 xxxx 10xx xxxx 10xx xxxx
            char2 = str.charCodeAt(i++);
            char3 = str.charCodeAt(i++);
            out += String.fromCharCode(((c & 0x0F) << 12) |
            ((char2 & 0x3F) << 6) |
            ((char3 & 0x3F) << 0));
            break;
            }
            }
            return out;
            }
        
           return {
            decode : function(str) { return utf8to16(decode64(str)); }, 
            encode : function(str) { return encode64(utf16to8(str)); }
         }; 
    };
    
    //通用函数
    function isUrl(url) {
        if (url && typeof(url) == "string" && url.constructor == String) {
            return true;
        }
        return false;
    }

    //获取文件名
    function getFileName(obj) {
        if (obj.getAttribute("filename")!="") {
             return obj.getAttribute("filename");
           } else{
             return "";
          }
    }

    //提示用户升级
    function updateXFTips(sid){
       if (window.confirm(unescape(Msg_update))) {window.location = MainURI+sid;}
    }
    
    //浏览器类型
    function GetNavigatorType() {
        var agent = navigator.userAgent.toLowerCase();
        if (/msie/i.test(agent) && !/opera/.test(agent)) {return NT_IE;} 
        if (/firefox/i.test(agent)){return NT_FIREFOX;} 
        if (/chrome/i.test(agent) && /webkit/i.test(agent) && /mozilla/i.test(agent)) {return NT_CHROME;} 
        if (/webkit/i.test(agent) && !(/chrome/i.test(agent) && /webkit/i.test(agent) && /mozilla/i.test(agent))) {return NT_SAFARI;}
        if (/opera/i.test(agent)) {return NT_OPERA;}
        return NT_UNKNOWN;
    }

    //创建IE对象
    function CreateIEObject() {
        if (g_comIEQQDownload === null) {
            try {
                g_comIEQQDownload = new ActiveXObject("QQIEHelper.QQRightClick.2");
            } catch (e) {
                return false;
            }
        }
        return true;
    }

    //Firefox,创建Firefox对象
    function CreateFFObject() {
        try {netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");} catch (e) {return false;}
        if (g_comFFDownload === null) {
            try {
                 //新的FF创建方式
                CreateQQDownloadFFH();
                 } catch (e) {
                 //兼容老的方式
                   try{
                       g_comFFDownload = Components.classes["@qq.com/QQDownloadFFH;1"].createInstance();
                       g_comFFDownload = g_comFFDownload.QueryInterface(Components.interfaces.IQQDownloadFFH);
                          }catch (e){
                            return false;
                           }    
            }
        }
        return true;
    }

    //新的Firefox对象创建方式,ctypes,支持之后的FF升级
    function CreateQQDownloadFFH()
    {
        //条件编译
        /*@cc_on
        /*@if (@_jscript)
          //IE
        @else @*/
        Components.utils.import("resource://gre/modules/ctypes.jsm");
        /*@end
        @*/
        if(g_libXFMid == null)
        {
            var file_pro = Components.classes["@mozilla.org/file/directory_service;1"].getService(Components.interfaces.nsIProperties).get("ProfD", Components.interfaces.nsIFile);   

            var dll_file = file_pro.path + "\\extensions\\{00000000-965C-475f-92C9-8D9EB7B27605}\\components\\XFFirefoxExtMid.dll";

            g_libXFMid = ctypes.open(dll_file); 
            
            if(g_libXFMid == null)
            {
                alert("CreateQQDownloadFFH g_libXFMid null");
                return;
            }
                    
            g_CheckFileType = g_libXFMid.declare("CheckFileType", ctypes.winapi_abi, ctypes.int32_t, ctypes.jschar.ptr);
            
            if(g_CheckFileType == null)
            {           
                alert("g_CheckFileType == null");
            }
            
            
            g_SendSingleTask4 = g_libXFMid.declare("SendSingleTask4", ctypes.winapi_abi, ctypes.int32_t, ctypes.jschar.ptr, ctypes.jschar.ptr,
                    ctypes.jschar.ptr, ctypes.jschar.ptr, ctypes.int32_t, ctypes.int32_t, ctypes.int32_t, 
                    ctypes.jschar.ptr, ctypes.jschar.ptr, ctypes.jschar.ptr);
            
            if(g_SendSingleTask4 == null)
            {           
                alert("g_SendSingleTask4 == null");
            }               
                    
                    
            g_AddTask3 = g_libXFMid.declare("AddTask3", ctypes.winapi_abi, ctypes.int32_t, ctypes.jschar.ptr, ctypes.jschar.ptr,
                    ctypes.jschar.ptr, ctypes.jschar.ptr, ctypes.int32_t, ctypes.jschar.ptr);   
                    
            if(g_AddTask3 == null)
            {           
                alert("g_AddTask3 == null");
            }       
                    
            g_SendMultiTask = g_libXFMid.declare("SendMultiTask", ctypes.winapi_abi, ctypes.void_t);
            
            if(g_SendMultiTask == null)
            {           
                alert("g_SendMultiTask == null");
            }
            
        }   
        
    }
     
    function OnIEDownloadClick(uri, ref, remark, cookie, rate, sid, filesize, filehash, filename) {
        if (CreateIEObject()) {
                g_comIEQQDownload.SendUrl4(uri, ref, remark, cookie, rate, sid, filesize, filehash, filename);
                return XF_OK;
        } else {
                return ERR_NOT_INSTALL_QQDOWNLOAD;
         }
    }

    function OnFFDownloadClick(uri, ref, remark, cookie, rate, sid, filesize, filehash, filename) {
        if (CreateFFObject()) {
            try {
                //新的FF插件调用
                filesize=filesize.toString();
                filehash=filehash.toString();
                filename=filename.toString();
                g_SendSingleTask4(uri, ref, remark, cookie, 1, rate, sid, filesize, filehash, filename);
            } catch (e) {
                //兼容旧的
                try{
                   g_comFFDownload.SendSingleTask4(uri, ref, remark, cookie, 1, rate, sid, filesize, filehash, filename);
                }catch(e){
                   //未成功
                   return ERR_NOT_INSTALL_QQDOWNLOAD;
                }
            }
            
        } else {
            try {netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");} catch (e) {return ERR_NOT_ENOUGH_PRIVILEGE;}
        }
        return XF_OK;
    }

    function OnDownloadClick(linkObj, evt, uri, ref, remark, cookie, rate, sid, filesize, filehash, filename, defaultopen, redirectUrl) {
        var ret = XF_OK;
        switch (GetNavigatorType()) {
        case NT_IE:
            ret = OnIEDownloadClick(uri, ref, remark, cookie, rate, sid, filesize, filehash, filename);
            g_comIEQQDownload = null;
            break;
        case NT_FIREFOX:
            ret = OnFFDownloadClick(uri, ref, remark, cookie, rate, sid, filesize, filehash, filename);
            break;
        case NT_CHROME:
            if(sid==10600){ret = ERR_NOT_SUPPORT_EXPLORER;}else{window.location=uri;}
            break;
        default:
            ret = ERR_NOT_SUPPORT_EXPLORER;
            break;
        }
        
        if(ret == XF_OK){
            return true;
        }else{
            updateXFTips(sid);
            return false;
        }
        
    }
    
    function onBatchDownloadClick(tasks) {
        var nType = GetNavigatorType();
        var tasklist=tasks.data;
        var task_count=tasklist.length;
        switch (nType) {
        case NT_IE: {
                if (CreateIEObject()) {
                    for (var i = 0; i < task_count;i++ ) {
                        if(!!tasklist[i].file_name==false){tasklist[i].file_name=""};
                        if(!!tasklist[i].cookie==false){tasklist[i].cookie=""};
                        g_comIEQQDownload.AddCmnInfo(tasklist[i].cookie);
                        g_comIEQQDownload.AddTask3(tasklist[i].url, "", "", 0, tasklist[i].file_name);
                    }
                    g_comIEQQDownload.SendMultiTask();
                } else {
                    updateXFTips(tasks.sid);
                }
            }
            break;
        case NT_FIREFOX: {
                try {
                    if (CreateFFObject()) {
                        for (var i = 0; i < task_count;i++ ) {
                            if(!!tasklist[i].file_name==false){tasklist[i].file_name=""};
                            if(!!tasklist[i].cookie==false){tasklist[i].cookie=""};
                            try {
                                //新方式
                                g_AddTask3(tasklist[i].url, "", "", tasklist[i].cookie, 0, tasklist[i].file_name);
                            } catch (e) {
                                //旧兼容
                                g_comFFDownload.AddTask3(tasklist[i].url,"" , "", tasklist[i].cookie, 0, tasklist[i].file_name);
                            }
                        }
                        try{g_SendMultiTask()}catch (e){g_comFFDownload.SendMultiTask();}
                    } else {
                        updateXFTips(tasks.sid);
                    }
                } catch (e) {
                        updateXFTips(tasks.sid);
                }
            }
            break;
        default: {
                updateXFTips(tasks.sid);
            }
            break;
        }   
    }
    

    function creat_xf_npapi(){
        //新版支持
        var nType = GetNavigatorType();
        if(nType==NT_IE){
           try {
                XF_NPAPI_PLUGIN= new ActiveXObject("QQIEHelper.QQRightClick.2");
                var xf_info=XF_NPAPI_PLUGIN.GetInfo().replace(/(^\s*)|(\s*$)/g, "");
                var version=JSON.parse(xf_info);
                return version.support_npapi;
            } catch (e) {
                return false;
            }
        }else{
            var get_xf_npapi = navigator.mimeTypes["application/npxf-qqdownload"];
            if (get_xf_npapi) {
                XF_NPAPI_PLUGIN = document.createElement("embed");
                XF_NPAPI_PLUGIN.style.visibility = "hidden";
                XF_NPAPI_PLUGIN.type = "application/npxf-qqdownload";
                XF_NPAPI_PLUGIN.width = 0;
                XF_NPAPI_PLUGIN.height = 0;
                document.body.appendChild(XF_NPAPI_PLUGIN);
                var version=JSON.parse(XF_NPAPI_PLUGIN.GetInfo());
                return version.support_npapi;
            } else {
                return false;
            }
        }
    }

    
    function startSingleDownload(linkObj, evt, sid, offline){
             
        var uri = "";
        var remark = "";
        var filename = "";
        var cookie = "";
        var ref = window.location+"#xfsid="+sid||"";
        
        if(linkObj){
            uri = linkObj.getAttribute('QHref')||"";
            remark = linkObj.innerText||linkObj.textContent||"";
            filename = getFileName(linkObj)||"";
            cookie = linkObj.getAttribute('FTN5K')||document.cookie;
        }else{
            var myevt=window.event?window.event:evt;
            var element = myevt.target||myevt.srcElement;
            uri = element.getAttribute('QHref')||"";
            remark = element.innerText||element.textContent||"";
            filename = getFileName(element)||"";
            cookie = linkObj.getAttribute('FTN5K')||document.cookie;
            }
        
        if(creat_xf_npapi()){
              var ver=JSON.parse(XF_NPAPI_PLUGIN.GetInfo().replace(/(^\s*)|(\s*$)/g, ""));
              if(filename!=""&&ver.xf_version==717&&offline==0){
                var base64= new TextUtil.base64();
                uri="qqdl://"+base64.encode(uri) + "\\" + base64.encode(filename);
              }
              //单个下载配置文件
              var single_task_config={
              "addto_offline":offline,
              "silence_create":0,
              "url_list":[{
              "url":uri,
              "file_name":filename,
              "custom_id":sid,
              "cookie":cookie,
              "file_size":"",
              "ref":ref,
              "remark":remark,
              "hash":"",
              "p2p_rate":0
              }]};
              //console.log(JSON.stringify(single_task_config));
              //开始调用
              XF_NPAPI_PLUGIN.StartTask(JSON.stringify(single_task_config));
        }else{
             if(offline){sid=10600};
             if (GetNavigatorType() == NT_CHROME) {
                updateXFTips(sid);
             } else {
                OnDownloadClick(linkObj, evt, uri, ref, remark, document.cookie, 0, sid, 0, 0, filename, false, true);
             }
             
        }       
    }
    
    function startBatchDownload(tasks,offline){
        if(creat_xf_npapi()){
              //新方式
              var batch_task_config={
              "addto_offline":offline,
              "silence_create":0,
              "url_list":[]
              };
              
              var tasklist=tasks.data;
              var task_count=tasklist.length;
              for (var i = 0; i < task_count;i++ ) {
                     var items_obj={
                      "url":tasklist[i].url,
                      "file_name":tasklist[i].file_name||"",
                      "custom_id":tasks.sid,
                      "cookie":tasklist[i].cookie||"",
                      "file_size":"",
                      "ref":window.location+"#xfsid="+tasks.sid,
                      "remark":"",
                      "hash":"",
                      "p2p_rate":0
                     };
                     batch_task_config.url_list.push(items_obj);
              }
              //开始调用
              XF_NPAPI_PLUGIN.StartTask(JSON.stringify(batch_task_config));
        }else{  
            updateXFTips(tasks.sid);    
        }
    }
    
    //是否安装了旋风
    XFLIB.IsXFInstalled=function(){
       if(creat_xf_npapi()){
          return true;
        }else{
            var nType = GetNavigatorType();
            if(nType==NT_IE){
               try {
                    XF_NPAPI_PLUGIN= new ActiveXObject("QQIEHelper.QQRightClick.2");
                    return true;
                } catch (e) {
                    return false;
                }
            }else{
                   return false;
            }  
       }
    }
    
    //右键另存为菜单
    XFLIB.OnContextClick=function(linkObj, evt) {
        if(linkObj){
            var link=linkObj.getAttribute("QHref");
            linkObj.setAttribute("href", link);
        }else{
            var myevt=window.event?window.event:evt;
            var element = myevt.target||myevt.srcElement;
            var link = element.getAttribute('QHref');
            element.setAttribute("href", link);
        }
    }
    
    //单个普通下载
    XFLIB.startDownload=function(linkObj, evt, sid) {
       startSingleDownload(linkObj, evt, sid ,0);
    }

    //单个离线下载
    XFLIB.startDownloadLixian=function(linkObj, evt, sid) {
       startSingleDownload(linkObj, evt, sid ,1);
    }
    
    //批量普通下载
    XFLIB.startDownload_BatchTask=function(tasks) {
       startBatchDownload(tasks,0);
    }

    //批量离线下载
    XFLIB.startDownload_BatchTaskLixian=function(tasks) {
      startBatchDownload(tasks,1);
    }

})();

