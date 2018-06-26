$(document).ready(function(){
  var serverURL = "";
// xmlhttpRequest objects setup
  var uploadRequest, dataRequest;
  if(window.XMLHttpRequest){
    uploadRequest=new XMLHttpRequest();
    dataRequest=new XMLHttpRequest();
  } else {
    uploadRequest=new ActiveXObject("Microsoft.XMLHTTP");
    dataRequest=new ActiveXObject("Microsoft.XMLHTTP");
  }
  // handle server responses
  uploadRequest.onreadystatechange=function(){
    if(uploadRequest.readyState == 4 && uploadRequest.status == 200) {
      // document.getElementById("myDiv").innerHTML = uploadRequest.responseText;
      console.console.log("upload response:");
      console.log(uploadRequest.responseText);
      // save as json & extract id
    }
  }

  dataRequest.onreadystatechange=function(){
    if(uploadRequest.readyState==4 && uploadRequest.status==200) {
      document.getElementById("myDiv").innerHTML=uploadRequest.responseText;
      // save as json & extract data and display on google maps
    }
  }


  //upload file
  $("#myFile").change(function() {
    var files = this.files;
    // var ext =
    if(files[0].name.split(".")[1] == "csv") {
      if (window.FileReader) {
        var reader = new FileReader();
        reader.readAsText(files[0]);
        // Handle errors and data
        reader.onload = processData;
        reader.onerror = errorHandler;
      } else {
        alert("FileReader is not supported in this browser.")
      }
    } else {
      alert("Incorrect file type!")
    }
  });


  function processData(event) {
    // Construct JSON from CSV
    var csv = event.target.result;
    var txt = csv.split(/\r\n|\n/);
    var headers = txt[0].split(", ");
    var validCsv = true;
    if (headers.length == 2 && headers[0] == "name"
      && headers[1] == "address") {
        var result = [];
        for (var i=1; i < txt.length; i++) {
          var data = txt[i].split(',"');
          if(data.length != 2 || data[0] === "" || data[1] === "") {
            alert("Invalid csv format!\n");
            validCsv = false;
            break;
          }
          var entry = {};
          entry[headers[0]] = data[0];
          entry[headers[1]] = data[1].replace('"','');
          result.push(entry);
        }
        if(validCsv) {
          console.log(JSON.stringify(result));
          dataRequest.open("POST",serverURL + "upload.php", true);
          dataRequest.setRequestHeader("data", JSON.stringify(result));
          dataRequest.send();
        }
    } else {
        alert("Invalid headers");
    }
  }

  function errorHandler(event) {
    if(event.target.error.name == "NotReadableError") {
      alert("Cannot read file!")
    }
  }

});
