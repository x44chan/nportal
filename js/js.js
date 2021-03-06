//test test
$(function(){	
	$("#regerr").on("click", function(){
        window.location.reload();
    });
});
function number_format(number, decimals, dec_point, thousands_sep) {
    var n = !isFinite(+number) ? 0 : +number, 
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        toFixedFix = function (n, prec) {
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            var k = Math.pow(10, prec);
            return Math.round(n * k) / k;
        },
        s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
$(document).ready(function(){
	$('#tbloginLog').DataTable({
        "iDisplayLength": 50,
        "order": [[ 1, "desc" ]],
        "info":     false
	});
	$('#oncall').click(function(){
	      $('#lg').attr('checked', false);
	      $('#restday').attr('checked', false);
	      $('#sw').attr('checked', false);
	      $(this).attr('checked', true);
	});
	$('#restday').click(function(){
	      $('#oncall').attr('checked', false);
	      $('#lg').attr('checked', false);
	      $('#sw').attr('checked', false);
	      $(this).attr('checked', true);
	});
	$('#sw').click(function(){
	      $('#oncall').attr('checked', false);
	      $('#restday').attr('checked', false);
	      $('#lg').attr('checked', false);
	      $(this).attr('checked', true);
	});
	$('#lg').click(function(){
	      $('#oncall').attr('checked', false);
	      $('#restday').attr('checked', false);
	      $('#sw').attr('checked', false);
	      $(this).attr('checked', true);
	});
    $("#regerr").click(function(){
        $("#regerr").attr("href", "admin.php?");
    });
    $('select[name = "empcatergory"]').change(function() {
    	var selected = $(this).val();
    	$('option:selected', this).attr('selected',true).siblings().removeAttr('selected');
    	if(selected != 'Contractual'){
    		$('input[name = "catdate"]').attr('required',true); 
      	}else{
        	$('input[name = "catdate"]').attr('required',false);
      	}
    });
    $('select[name = "servicecomp"]').change(function() {
    	var selected = $(this).val();
    	if(selected == 'new'){
    		$('#sernew').show();
    		$('input[name = "email"]').attr('required',true); 
    		$('input[name = "cname"]').attr('required',true); 
      	}else{
      		$('#sernew').hide();
      		$('input[name = "email"]').attr('required',false); 
      		$('input[name = "cname"]').attr('required',false); 
      	}
    });
    $('select[name = "proj_type"]').change(function() {
    	var selected = $(this).val();
    	if(selected == 'Commission Base' || selected == 'Project' || selected == 'Support'){
    		$('#proj_loc').show();
    		$('input[name = "proj_loc"]').attr('required',true);
      	}else{
      		$('#proj_loc').hide();
      		$('input[name = "proj_loc"]').attr('required',false); 
      		
      	}
      	if(selected == 'Commission Base'){
    		$('#comisiontypex').show();
    		$('input[name = "comisiontypex"]').attr('required',true);
      	}else{
      		$('#comisiontypex').hide();
      		$('input[name = "comisiontypex"]').attr('required',false); 
      		
      	}
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
	$('select[name="otcomisiontype"]').change(function() {
	    var selected3 = $(this).val();
	    if(selected3 == 'Bidding'){
			$('#otcomisionbid').show();
			$('select[name="otcomisionbid"]').attr('required',true);
		}else{
			$('#otcomisionbid').hide();
			$('select[name="otcomisionbid"]').attr('required',false);
			$('select[name="otcomisionbid"]').val('');
		}
		if(selected3 == 'Project'){
			$('#otcomisionproj').show();
			$('select[name="otcomisionproj"]').attr('required',true);
		}else{
			$('#otcomisionproj').hide();
			$('select[name="otcomisionproj"]').attr('required',false);
			$('select[name="otcomisionproj"]').val('');
		}
	});
	$('select[name="comisiontype"]').change(function() {
	    var selected3 = $(this).val();
	    if(selected3 == 'Bidding'){
			$('#comisionbid').show();
			$('select[name="comisionbid"]').attr('required',true);
		}else{
			$('#comisionbid').hide();
			$('select[name="comisionbid"]').attr('required',false);
			$('select[name="comisionbid"]').val('');
		}
		if(selected3 == 'Project'){
			$('#comisionproj').show();
			$('select[name="comisionproj"]').attr('required',true);
		}else{
			$('#comisionproj').hide();
			$('select[name="comisionproj"]').attr('required',false);
			$('select[name="comisionproj"]').val('');
		}
	});
	$('select[name="pettype"]').change(function() {
	    var selected2 = $(this).val();
		if(selected2 == 'Company Vehicle'){
			$('#com_car').show();
			$('select[name="com_car"]').attr('required',true);
		}else{
			$('#com_car').hide();
			$('select[name="com_car"]').attr('required',false);
		}
		if(selected2 == 'Social Payments'){
			$('#social').show();
			$('select[name="social"]').attr('required',true);
		}else{
			$('#social').hide();
			$('select[name="social"]').attr('required',false);
		}
		if(selected2 == 'Corporate'){
			$('#corpo').show();
			$('select[name="corpo"]').attr('required',true);
		}else{
			$('#corpo').hide();
			$('select[name="corpo"]').attr('required',false);
		}
		if(selected2 == 'Email Hosting'){
			$('#ehosting').show();
			$('select[name="ehosting"]').attr('required',true);
		}else{
			$('#ehosting').hide();
			$('select[name="ehosting"]').attr('required',false);
		}
		if(selected2 == 'Supplier'){
			$('#supp').show();
			$('select[name="supp"]').attr('required',true);
		}else{
			$('#supp').hide();
			$('select[name="supp"]').attr('required',false);
		}
		if(selected2 == 'Project'){
			$('#project').show();
			$('select[name="loc"]').val("");
			$('select[name="project"]').attr('required',true);
		}else{
			$('#project').hide();
			document.getElementById("locx").innerHTML = "";
			$('select[name="project"]').attr('required',false);
			$('select[name="loc"]').val("");
		}
		if(selected2 == 'Support'){
			$('#support').show();
			$('select[name="loc"]').val("");
			$('select[name="project"]').attr('required',true);
		}else{
			$('#support').hide();
			document.getElementById("locx").innerHTML = "";
			$('select[name="project"]').attr('required',false);
			$('select[name="locx"]').val("");
		}
		if(selected2 == 'House'){
			$('#house').show();
			$('select[name="house"]').attr('required',true);
		}else{
			$('#house').hide();
			$('select[name="house"]').attr('required',false);
		}
		if(selected2 == 'P.M.'){
			$('#pm').show();
			$('select[name="pm"]').attr('required',true);
		}else{
			$('#pm').hide();
			$('select[name="pm"]').attr('required',false);
		}
		if(selected2 == 'Internet'){
			$('#internet').show();
			$('select[name="internet"]').attr('required',true);
		}else{
			$('#internet').hide();
			$('select[name="internet"]').attr('required',false);
		}
		if(selected2 == 'Commission Base'){
			$('#comisiontype').show();
			$('select[name="comisiontype"]').attr('required',true);
			$('select[name="comisiontype"]').val('');
		}else{
			$('#comisiontype').hide();
			$('#comisionbid').hide();
			$('#comisionproj').hide();
			$('select[name="comisiontype"]').attr('required',false);
		}
		if(selected2 == 'All'){
			$('#all').show();
			$('select[name="internet"]').attr('required',true);
		}else{
			$('#all').hide();
			$('select[name="internet"]').attr('required',false);
		}
		if(selected2 == 'Combined'){
			$('#combined').show();
			$('select[name="combined"]').attr('required',true);
		}else{
			$('#combined').hide();
			$('select[name="combined"]').attr('required',false);
		}
		if(selected2 == 'Service' || selected2 == 'Service'){
			$('#oncallxx').show();
			$('select[name="xoncall"]').attr('required',true);
		}else{
			$('#oncallxx').hide();
			$('select[name="xoncall"]').attr('required',false);
		}
	});
	$('select[name="ottype"]').change(function() {
		    var selected2 = $(this).val();
			
			if(selected2 == 'P.M.'){
				$('#otpm').show();
				$('select[name="otpm"]').attr('required',true);
			}else{
				$('#otpm').hide();
				$('select[name="otpm"]').attr('required',false);
			}
			if(selected2 == 'Corporate'){
				$('#otcorpo').show();
				$('select[name="otcorpo"]').attr('required',true);
			}else{
				$('#otcorpo').hide();
				$('select[name="otcorpo"]').attr('required',false);
			}
			if(selected2 == 'Internet'){
				$('#otinternet').show();
				$('select[name="otinternet"]').attr('required',true);
			}else{
				$('#otinternet').hide();
				$('select[name="otinternet"]').attr('required',false);
			}
			if(selected2 == 'Commission Base'){
				$('#otcomisiontype').show();
				$('select[name="otcomisiontype"]').attr('required',true);
			}else{
				$('#otcomisiontype').hide();
				$('select[name="otcomisiontype"]').attr('required',false);
			}
			if(selected2 == 'Service'){
				$('#otoncall').show();
				$('select[name="otoncall"]').attr('required',true);
			}else{
				$('#otoncall').hide();
				$('select[name="otoncall"]').attr('required',false);
			}
			if(selected2 == 'Email Hosting'){
				$('#otehosting').show();
				$('select[name="otehosting"]').attr('required',true);
			}else{
				$('#otehosting').hide();
				$('select[name="otehosting"]').attr('required',false);
			}
			if(selected2 == 'Project'){
				$('#otproject').show();
				$('select[name="loc"]').val("");
				$('select[name="otproject"]').attr('required',true);
				$('select[name="loc"]').attr('required',true);
			}else{
				$('#otproject').hide();
				document.getElementById("loc").innerHTML = "";
				$('select[name="otproject"]').attr('required',false);
				$('select[name="loc"]').attr('required',false);
			}
			if(selected2 == 'Support'){
				$('#otsupport').show();
				$('select[name="locx"]').val("");
				$('select[name="otproject"]').attr('required',true);
				$('select[name="locx"]').attr('required',true);
			}else{
				$('#otsupport').hide();
				document.getElementById("loc").innerHTML = "";
				$('select[name="otproject"]').attr('required',false);				
				$('select[name="locx"]').attr('required',false);
			}
		});
	$("#petamount").keyup(function(e){
        $(this).val(format($(this).val()));
    });
    $("#rebamount").keyup(function(e){
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
    	$("#latefiling").hide();
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
    	$("#latefiling").hide();
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
    	$("#latefiling").hide();
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
    	$("#latefiling").hide();
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
		location.reload();
    });
});


$(function(){
    $("#hideob").on("click", function(){
		$("#offb").hide();
		$("#dash").show();
		location.reload();
    });
});


$(function(){
    $("#hideout").on("click", function(){
		$("#undertime").hide();
		$("#dash").show();
		location.reload();
    });
});