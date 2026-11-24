
const validator = {
    soloNumeros: function(event){
        var code = (event.which) ? event.which : event.keyCode;

        if(code==8) { // backspace.
            return true;
        } else if(code>=48 && code<=57) { // is a number.
            return true;
        } else{ // other keys.
            return false;
        }
    },

    soloLetras: function (event) {
        if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
			event.returnValue = false;
    }
}