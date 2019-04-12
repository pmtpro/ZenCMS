/**
*     Jquery Plugin Compiler
*  it's JQ Plugin extends Snippet JQuery Plugin of SteamDev
*    Copyright 2013, Abdennour
*
*/
(function($){
  $.fn.snpmyjq;
      $.fn.snpctns=[];
    	$.fn.snpbefore;
        $.fn.snpafter;
    	var methodssnipcach={
    	    addone:function(jqthis,srccd,args){
    	         var keygen=methodssnipcach.keyfy(jqthis, srccd, args)
                
    	         $.fn.snpbefore=jqthis
    	            if($.fn.snpctns[keygen]){
    	            }
    	         else{ 
    	               var mk=methodssnipcach.append(jqthis)
    	                mk.snippet(srccd,args);
    	                $.fn.snpctns[keygen]=$('body>:last-child').html();
                    
    	                $('body>:last-child').remove();
    	             
    	                }
    	                jqthis.replaceWith($.fn.snpctns[keygen])
    	    },
    	    addonesess:function(jqthis,srccd,args){
    	        var keygen=methodssnipcach.keyfy(jqthis, srccd, args)
    	         $.fn.snpbefore=jqthis
    	            if(sessionStorage.getItem(keygen)){
    	            }
    	         else{
    	             
    	               var mk=methodssnipcach.append(jqthis)
    	                mk.snippet(srccd,args);
    	                sessionStorage.setItem(keygen,$('body>:last-child').html());
    	                 
    	 
    	 $('body>:last-child').remove();
    	             
    	                }
    	                jqthis.replaceWith(sessionStorage.getItem(keygen))
    	    },
    	        local:function(srccd,args){
    	          
    	            if($.fn.snpmyjq.length==1){
    	              methodssnipcach.addone($.fn.snpmyjq,srccd,args);
    	            }else{
    	                
    	                 $.fn.snpmyjq.each(function(i,e){
    	                   methodssnipcach.addone($(e),srccd,args);                 
    	                }) 
    	            }
    	        },
    	    session:function(srccd,args){
    	          
    	            if($.fn.snpmyjq.length==1){
    	               methodssnipcach.addonesess($.fn.snpmyjq,srccd,args);
    	            }else{
    	                 $.fn.snpmyjq.each(function(i,e){
    	                   methodssnipcach.addonesess($(e),srccd,args);                 
    	                }) 
    	            }
    	        },
    	        append:function(jq){
    	            var mk=jq.clone();
    	            mk.css('display','none');
    	           mk.appendTo($('body'));
    	           return mk;
    	        }, 
    	        unappend:function(){
    	        
    	        },keyfy:function(jq,src,args){
    	        	return jq.text()+JSON.stringify(args)+src;
    	        },getItems:function(srccd,args){
    	        	var keygen=srccd+JSON.stringify(args);
    	        	return methodssnipcach.getvalues($.fn.snpctns,keygen);
    	        },
    	        getvalues:function (arr,keyctn)
    	        {
    	            var values = []; 

    	            for(var key in arr)
    	            {
    	                if(key.indexOf(keyctn)!==-1)
    	                {
    	                    values.push(arr[key]);
    	                }
    	            }

    	            return values;
    	        }

    	    }
    	 /**@depend jquery.snippet.min.js / jquery.snippet.min.css
         * $('pre').snippetcache('local','','java',{style:'ide-eclipse',box:"all"})
         */
    	    $.fn.snippetcache=function(method,srccd,args){
    	        if ($(this).length!==0){
    	            $.fn.snpmyjq=$(this);
    	             return methodssnipcach[method].apply(this, Array.prototype.slice.call(arguments, 1))  
    	        }     
    	                     
    	    }	


})(jQuery)
