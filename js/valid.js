function valid() {
    var fi_na = document.register.fi_na.value;
    var la_na = document.register.la_na.value;
    var gen = document.register.gen.value;
    var bir = document.register.bir.value;
    var hei = document.register.hei.value;
    var wei = document.register.wei.value;
    var phno = document.register.phno.value;
    var email = document.register.email.value;
    var password = document.register.password.value;
    var password2 = document.register.password2.value;
    var num = /^[0-9]+$/;
    var has = /@gmail.com/;
    
    if(fi_na == "" || fi_na == null) {
        alert("enter your first name");
        document.register.fi_na.focus();
        return false;
    }
    
    if(la_na == "" || la_na == null) {
        alert("enter your second name");
        document.register.la_na.focus();
        return false;
    }
    
    if(gen == "" || gen == null) {
        alert("please select your gender");
        document.register.gen.focus();
        return false;
    }
    
    if(bir == "" || bir == null) {
        alert("enter your DOB");
        document.register.bir.focus();
        return false;
    }
    
    if(hei == "" || hei == null || !hei.match(num)) {
        alert("please enter your height correctly");
        document.register.hei.focus();
        return false;
    }
    
    if(wei == "" || wei == null || !wei.match(num)) {
        alert("please enter your weight correctly");
        document.register.wei.focus();
        return false;
    }
    
    if(email == "" || email == null || !email.match(has)) {
        alert("please enter a valid email (@gmail.com)");
        document.register.email.focus();
        return false;
    }
    
    if(phno == "" || phno == null || !phno.match(num) || phno.length != 10) {
        alert("Enter your phone number correctly (10 digits)");
        document.register.phno.focus();
        return false;
    }
    
    if(password == "" || password == null) {
        alert("set your password");
        document.register.password.focus();
        return false;
    }
    
    if(password2 == "" || password2 == null) {
        alert("Re-type your password");
        document.register.password2.focus();
        return false;
    }
    
    if(password2 != password) {
        alert("passwords don't match");
        document.register.password2.focus();
        return false;
    }
    
    alert("registration successful");
    return true;
}