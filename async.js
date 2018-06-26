$(document).ready(function(){
  var serverURL = "http://localhost:80/";
// xmlhttpRequest objects setup
  var uploadRequest, dataRequest, idRequest;
  if(window.XMLHttpRequest){
    uploadRequest=new XMLHttpRequest();
    dataRequest=new XMLHttpRequest();
    idRequest=new XMLHttpRequest();
  } else {
    uploadRequest=new ActiveXObject("Microsoft.XMLHTTP");
    dataRequest=new ActiveXObject("Microsoft.XMLHTTP");
    idRequest=new ActiveXObject("Microsoft.XMLHTTP");
  }

  // handle server responses
  $("#find").click(function() {
    let id  = $("#options").val());
    dataRequest.open("GET",serverURL + "data.php?id="+id, true);
    dataRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    dataRequest.send();
  });

  dataRequest.onreadystatechange=function(){
    if(dataRequest.readyState==4 && dataRequest.status==200) {
      document.getElementById("myDiv").innerHTML=uploadRequest.responseText;
      // save as json & extract data and display on google maps
    }
  }

  //upload file functions
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
          uploadRequest.open("GET",serverURL + "upload.php?data="+JSON.stringify(result)
            .replace("\"","%22").replace(" ","%20"), true);
          uploadRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          uploadRequest.send();
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

  uploadRequest.onreadystatechange=function(){
    if(uploadRequest.readyState == 4 && uploadRequest.status == 200) {
      try {
        var json = JSON.parse(uploadRequest.responseText);
        if (json.type == "fail") {
          alert("Invalid data from CSV or connection failure.")
          return;
        }
        $("#upload > p").html("Data added to id: "+json.id);
        addToOptions(json.id);
      } catch (e) {
        alert("Server error!");
      }

    }
  }
  // populate id list
  idRequest.onreadystatechange=function(){
    if(idRequest.readyState==4 && idRequest.status==200) {
      var list = JSON.parse(idRequest.responseText);
      for (var i = 0; i < list.length; i++) {
        addToOptions(list[i]);
      }
    }
  }
  function addToOptions(id) {
    $("#options").append("<option value=\""+id+"\">" + id+"</option>");
  }

  idRequest.open("GET",serverURL + "getids.php", true);
  idRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  idRequest.send();
});
