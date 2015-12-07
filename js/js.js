//test test
$(function(){	
	$("#regerr").on("click", function(){
        window.location.reload();
    });
});
$(document).ready(function(){
	
    $("#regerr").click(function(){
        $("#regerr").attr("href", "admin.php?");
    });
	$('#typeoflea').change(function() {
    var selected = $(this).val();
	
	if(selected == 'Others'){
		$('#othersl').attr('disabled',false);
		$("#othersl").attr('required',true);
		$('#othersl').attr("placeholder", "Enter Type of Leave");
	}else{
		$('#othersl').val("");
		$('#othersl').attr('disabled',true);
		$('#othersl').attr("placeholder", " ");
		$("#othersl").attr('required',false);
	}
});
	$("#petamount").keyup(function(e){
        $(this).val(format($(this).val()));
    });
    $("#uppet").keyup(function(e){
        $(this).val(format($(this).val()));
    });
	//auto add comma in amount
	var format = function(num){
	    var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
	    if(str.indexOf(".") > 0) {
	        parts = str.split(".");
	        str = parts[0];
	    }
	    str = str.split("").reverse();
	    for(var j = 0, len = str.length; j < len; j++) {
	        if(str[j] != ",") {
	            output.push(str[j]);
	            if(i%3 == 0 && j < (len - 1)) {
	                output.push(",");
	            }
	            i++;
	        }
	    }
	    formatted = output.reverse().join("");
	    return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
	};
});

//admin jquery
$(function(){	
	$("#needaproval").show();
	$("#showneedapproval").on("click", function(){
        $("#needaproval").show();
		$("#officialbusiness").show();
		$("#officialbusinessdisapprove").show();
		$("#officialbusinessapprove").hide();
		$("#officialbusinessdisapprove").hide();
		$("#officialbusinessdisapprove").hide();
		$("#approved").hide();
		$("#newuser").hide();	
		$("#disapproved").hide();
		$("#regerror").hide();
    });
});
$(function(){	
	$("#newuser").hide();
	$("#newuserbtn").on("click", function(){
        $("#newuser").show();
		$("#officialbusinessapprove").hide();
		$("#officialbusiness").hide();
		$("#approved").hide();
		$("#needaproval").hide();
		$("#disapproved").hide();
		$("#regerror").hide();
		$("#dash").hide();		
	});
});
$(function(){	
	$("#approved").hide();
	$("#officialbusinessapprove").hide();
	$("#officialbusinessdisapprove").hide();
	$("#showapproved").on("click", function(){
        $("#approved").show();
		$("#needaproval").hide();
		$("#newuser").hide();
		$("#regerror").hide();
		$("#disapproved").hide();
		$("#officialbusiness").hide();
		$("#officialbusinessapprove").show();
    });
});
$(function(){	
	$("#disapproved").hide();
	$("#showdispproved").on("click", function(){
    $("#disapproved").show();
		$("#officialbusiness").hide();
		$("#officialbusinessapprove").hide();
		$("#officialbusinessdisapprove").show();
		$("#newuser").hide();
		$("#regerror").hide();
		$("#approved").hide();
		$("#needaproval").hide();
    });
});
//employee jquery
$(function(){	
	$("#home").on("click", function(){
        $("#dash").show();
		$("#penot").show();
		$("#officialbusiness").show();
		$("#offb").hide();
		$("#undertime").hide();
		$("#formhidden").hide();
		$("#needaproval").hide();		
		$("#approvedrequest").hide();
		$("#disapprovedrequest").hide();
		$("#officialbusinessapprove").hide();
    });
});
$(function(){
    $("#formhidden").hide();
    $("#newovertime").on("click", function(){
        $("#formhidden").toggle();
		$("#approvedrequest").hide();
		$("#leave").hide();
		$("#dash").hide();
		$("#disapprovedrequest").hide();
		$("#officialbusiness").hide();
		$("#userlist").hide();
		$("#needaproval").hide();
		$("#appob").hide();
		$("#appot").hide();
		$("#appleave").hide();
		$("#appundr").hide();
		$("#dappob").hide();
		$("#dappot").hide();
		$("#dappleave").hide();
		$("#disappundr").hide();
		$("#disappleave").hide();
		$("#report").hide();

	});
});

$(function(){
    $("#leave").hide();
    $("#newleave").on("click", function(){
        $("#leave").show();
		$('#typeoflea').focus().select();
		$("#approvedrequest").hide();
		$("#dash").hide();
		$("#disapprovedrequest").hide();
		$("#officialbusiness").hide();
		$("#undertime").hide();
		$("#offb").hide();
		$("#formhidden").hide();
		$("#userlist").hide();
		$("#needaproval").hide();
		$("#appob").hide();
		$("#appot").hide();
		$("#appleave").hide();
		$("#appundr").hide();
		$("#dappob").hide();
		$("#dappot").hide();
		$("#dappleave").hide();
		$("#disappundr").hide();
		$("#disappleave").hide();
		$("#report").hide();
	});
});

$(function(){
    $("#undertime").hide();
    $("#newundertime").on("click", function(){
        $("#undertime").show();
		$("#formhidden").hide();
		$("#approvedrequest").hide();
		$("#disapprovedrequest").hide();
		$("#officialbusiness").hide();
		$("#offb").hide();
		$("#dash").hide();
		$("#userlist").hide();
		$("#leave").hide();
		$("#needaproval").hide();
		$("#appob").hide();
		$("#appot").hide();
		$("#appleave").hide();
		$("#appundr").hide();
		$("#dappob").hide();
		$("#dappot").hide();
		$("#dappleave").hide();
		$("#disappundr").hide();
		$("#disappleave").hide();
		$("#report").hide();
	});
});
$(function(){
    $("#offb").hide();
    $("#newoffb").on("click", function(){
        $("#offb").show();
		$("#dash").hide();
		$("#undertime").hide();
		$("#formhidden").hide();
		$("#approvedrequest").hide();
		$("#officialbusiness").hide();
		$("#disapprovedrequest").hide();
		$("#userlist").hide();
		$("#leave").hide();
		$("#needaproval").hide();
		$("#appob").hide();
		$("#appot").hide();
		$("#appleave").hide();
		$("#appundr").hide();
		$("#dappob").hide();
		$("#dappot").hide();
		$("#dappleave").hide();
		$("#disappundr").hide();
		$("#disappleave").hide();
		$("#report").hide();
	});
});

$(function(){
    $("#formhidden").hide();
    $("#newovertime").on("click", function(){
        $("#formhidden").show();
		$("#offb").hide();
		$("#approvedrequest").hide();
		$("#dash").hide();
		$("#undertime").hide();
		$("#disapprovedrequest").hide();
		$("#userlist").hide();
		$("#needaproval").hide();
		$("#leave").hide();
		$("#appob").hide();
		$("#appot").hide();
		$("#appleave").hide();
		$("#appundr").hide();
		$("#dappob").hide();
		$("#dappot").hide();
		$("#dappleave").hide();
		$("#disappundr").hide();
		$("#disappleave").hide();
		$("#report").hide();
	});
});
$(function(){
    $("#approvedrequest").hide();
    $("#myapprove").on("click", function(){
        $("#approvedrequest").show();
		$("#officialbusiness").hide();
		$("#offb").hide();
		$("#formhidden").hide();
		$("#dash").hide();
		$("#disapprovedrequest").hide();
		

    });
});
$(function(){
    $("#disapprovedrequest").hide();
    $("#mydisapprove").on("click", function(){
		$("#offb").hide();
		$("#officialbusiness").hide();
        $("#disapprovedrequest").show();
		$("#formhidden").hide();
		$("#dash").hide();
		$("#approvedrequest").hide();
    });
});


$(function(){
    $("#hideot").on("click", function(){
        $("#formhidden").hide();
		$("#offb").hide();
		$("#leave").hide();
		$("#dash").show();
    });
});


$(function(){
    $("#hideob").on("click", function(){
		$("#offb").hide();
		$("#dash").show();
    });
});


$(function(){
    $("#hideout").on("click", function(){
		$("#undertime").hide();
		$("#dash").show();
    });
});