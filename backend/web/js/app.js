$(function() {
    //Initialize Select2 Elements
    $(".select2").select2({
        'allowClear': true,
        'minimumResultsForSearch': 10,
    });

    $("#insert-page").on('click',function(){
        $("#template-content").append("{段落}");
    });

    $("#insert-image").on('click',function(){
        $("#template-content").append("{图片}");
    });

    $("#articlemix-product_id").on('change',function(){
        var website_id=$("#articlemix-website_id").val();
        if(website_id){
            $.get("/longtail-keywords/by_site_product",{website_id:website_id,product_id:$(this).val()},function ($data){
                $("#articlemix-longtail_keywords_ids").html($data);
            });
        }
    });
});
