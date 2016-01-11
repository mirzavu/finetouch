jQuery( document ).ready(function() {    
    jQuery(".tire-price-container").hover(
    	    function(){       	    	
    	    	jQuery(this).find('.orderlist-tireprices').slideDown('fast');
    	    },
    	    function(){    	    	
    	    	jQuery(this).find('.orderlist-tireprices').hide();
    	    }
    );    
});