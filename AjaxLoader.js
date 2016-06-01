/**
 * AjaxLoader extension
 */

window.wfAjaxLoader = function(el)
{
    var page = el.getAttribute('data-page');
    var params = el.getAttribute('data-params') || '';
    var closetext = el.getAttribute('data-closetext');
    var opentext = el.getAttribute('data-opentext');
    var content = el.nextSibling;
    while (content && content.className != 'ajaxLoader')
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
                }
            });
        }
        else
        {
            el.innerHTML = closetext;
            content.style.display = '';
        }
    }
    else
    {
        el.innerHTML = opentext;
        content.style.display = 'none';
    }
}
