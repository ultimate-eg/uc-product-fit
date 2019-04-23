var sizes = document.getElementById('calculateSizeBtn');
var data = sizes.getAttribute('data');
var data = data.substring(0, data.length - 1);
data = JSON.parse(data)
var inputsArray = [];
currentInput = 0;

for (var j = 0; j< data[0].size_attributes.length; j++) { 
    inputsArray.push(data[0].size_attributes[j].type);
}



function calculateSize() {
    var checkSize = false;
    var size = '';
    for (var i = 0; i<data.length; i++) {
        for (var j = 0; j< data[i].size_attributes.length; j++){
            var input = data[i].size_attributes[j].type;
            var typeValue = document.getElementById(input).value;
            typeValue = parseInt(typeValue);
            if (typeValue >= parseInt(data[i].size_attributes[j].from) && typeValue <= parseInt(data[i].size_attributes[j].to) ) {
                checkSize = true;
            } else {
                checkSize = false;
                break;
            }
        }
        if (checkSize) {
            size = data[i].size;
        }
    }
    if (size === "" ) {
        document.getElementById('size-result').innerHTML = '<div class="alert alert-warning"><h1 class="size-result">Sorry , We can not get suitable size for your inputs</h1></div>';
    }else{
        document.getElementById('size-result').innerHTML = '<div class="alert alert-info"><h1 class="size-result">your expected size is ' + size + '</h1></div>';
    }
    
    for (var j = 0; j< data[0].size_attributes.length; j++) { 
        document.getElementById(inputsArray[j]).value = '';
    }

   }



   function displaySizeWizard() {
    document.getElementById('size-result').innerHTML = '';
        document.getElementById(inputsArray[0]).style.display = 'block';
        currentInput=0;
        document.getElementById('size-wizard').style.display = 'none';
        document.getElementById('size-wizard-next').style.display = 'block';
   }

   function nextInput() {
    if ( document.getElementById(inputsArray[currentInput]).value > 0 ) {
        document.getElementById('size-result').innerHTML = '';
        document.getElementById(inputsArray[currentInput]).style.display = 'none'
        currentInput ++;
        if (currentInput < inputsArray.length) {
             document.getElementById(inputsArray[currentInput]).style.display = 'block'
        } else {
             document.getElementById('size-wizard-next').style.display = 'none';
             document.getElementById('size-wizard').style.display = 'block';
             document.getElementById('size-wizard').value = 'Check Your Size Again';
             calculateSize();
        }
    } else {
        document.getElementById('size-result').innerHTML = '<div class="alert alert-warning"><h1 class="size-result">Please Enter The ' + inputsArray[currentInput] + '</h1></div>';
    }   

   }


