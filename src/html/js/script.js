var myScript = function() {
	toastr.options = {
	  "closeButton": false,
	  "debug": false,
	  "newestOnTop": true,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};	
}

function compareDate(date1,date2) {
	var diff = new Date(date2-date1);	
	ye = Math.round(diff/1000/60/60/24/365);
	if (ye>0) return { d: ye, u: ye<2?'yr':'yrs' }
	else {
		mo = Math.round(diff/1000/60/60/24/30);
		if (mo>0) return { d: mo, u: mo<2?'mth':'mths' }
		else {
			da  = Math.round(diff/1000/60/60/24);
			if (da>0) return { d: da, u: da<2?'day':'days' }
			else {
				ho  = Math.round(diff/1000/60/60);				
				if (ho>0) return { d: ho, u: ho<2?'hr':'hrs' }
				else {
					mi  = Math.round(diff/1000/60);
					return { d: mi, u: mi<2?'Just now':'mins' }
				}
			}
		}
	}
}

function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element)
    node.classList.add('animated', animationName)

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }

    node.addEventListener('animationend', handleAnimationEnd)
}

jQuery(document).ready(function() {
    myScript();
});