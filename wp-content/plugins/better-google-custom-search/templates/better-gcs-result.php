<script>
    function BS_GCSE_Callback() {

        if (document.readyState != 'complete') {
            return google.setOnLoadCallback(BS_GCSE_Callback, true);
        }

        google.search.cse.element.render({
            gname: 'gsearch',
            div: 'better-gcs-wrapper',
            tag: 'search',
            attributes: {linkTarget: ''}
        });

        var element = google.search.cse.element.getElement('gsearch');
        element.execute('{{SEARCH-QUERY}}');
    };

    window.__gcse = {
        parsetags: 'explicit',
        callback: BS_GCSE_Callback
    };

    (function () {
        var cx = '{{SEARCH-ENGINE-ID}}';
        var gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
    })();
</script>
<div id="better-gcs-wrapper" class="better-gcs-wrapper {{SEARCH-CLASS}}">
	<div id="bs-gcse-results-loading">{{SEARCH-LOADING}}</div>
</div>
