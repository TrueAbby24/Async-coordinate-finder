$(document).ready(function(){
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
    if(uploadRequest.readyState==4 && uploadRequest.status==200) {
      document.getElementById("myDiv").innerHTML=uploadRequest.responseText;
      // save as json & extract id
    }
  }

  uploadRequest.onreadystatechange=function(){
    if(uploadRequest.readyState==4 && uploadRequest.status==200) {
      document.getElementById("myDiv").innerHTML=uploadRequest.responseText;
      // save as json & extract data and display on google maps
    }
  }


  //upload file
  $("#myFile").change(function() {
    //check everything not corrupted, check CSV myFile
    // console.log("uploaded");
    // // Process CSV
    // var x = $("#myFile");
    // console.log("x: " + x);
    // var fileReader = new FileReader();
    // fileReader.onload = function(fileLoadedEvent) {
    //   var txt = fileLoadedEvent.target.result;
    //   console.log(txt);
    // };
    //
    // fileReader.readAsText(x,"UTF-8");
    var files = this.files;
    console.log(files);
    if (window.FileReader) {
      var reader = new FileReader();
      reader.readAsText(files[0]);
      // Handle errors and data
      reader.onload = processData;
      reader.onerror = errorHandler;
    } else {
      alert("FileReader is not supported in this browser.")
    }
  });


  function processData(event) {
    var csv = event.target.result;
    var allTextLines = csv.split(/\r\n|\n/);
    var lines = [];
    for (var i=0; i < allTextLines.length; i++) {
        var data = allTextLines[i].split(',');
            var tarr = [];
            for (var j=0; j<data.length; j++) {
                tarr.push(data[j]);
            }
            lines.push(tarr);
    }
  console.log(lines);
  }

  function errorHandler(event) {
    if(event.target.error.name == "NotReadableError") {
      alert("Cannot read file!")
    }
  }

});
