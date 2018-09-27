$(document).ready(function () {
    $('input#post_seoTitle, input#post_seoDescription, ' +
        'input#post_seoFacebookTitle, input#post_seoFacebookDescription, ' +
        'input#post_seoTwitterTitle, input#post_seoTwitterDescription')
        .characterCounter();
});