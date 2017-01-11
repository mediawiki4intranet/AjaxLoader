/**
 * AjaxLoader extension
 */

window.wfAjaxLoader = function(el)
{
    var page = el.getAttribute('data-page');
    var params = el.getAttribute('data-params') || '';
    var closetext = el.getAttribute('data-closetext');
    var opentext = el.getAttribute('data-opentext');
    var header = el.parentNode;
    var content = header;
    while (content && (!content.className || content.className.indexOf('ajaxLoader') < 0))
        content = content.nextSibling;
    var do_open = content.style.display == 'none';
    if (do_open)
    {
        if (!content.innerHTML)
        {
            el.setAttribute('data-opentext', el.innerHTML);
            $.ajax({
                url: mw.util.wikiScript()+'?title='+encodeURIComponent(page)+
                    '&action=render'+(params ? '&'+params : ''),
                type: 'GET',
                dataType: 'html',
                success: function(r)
                {
                    el.innerHTML = closetext;
                    content.innerHTML = r;
                    content.style.display = '';
                    content.className = 'ajaxLoader ajaxLoaderOpened';
                    header.className = 'ajaxLoadHeaderOpened';
                }
            });
        }
        else
        {
            el.innerHTML = closetext;
            content.style.display = '';
            content.className = 'ajaxLoader ajaxLoaderOpened';
            header.className = 'ajaxLoadHeaderOpened';
        }
    }
    else
    {
        el.innerHTML = opentext;
        content.style.display = 'none';
        content.className = 'ajaxLoader ajaxLoaderClosed';
        header.className = 'ajaxLoadHeaderClosed';
    }
}
