function search_w() {
  var writer = document.getElementById("writer").value;
  $.ajax({
    type: "POST",
    url: "search_w.php",
    data: {
      writer: writer
    },
    success: function(data) {
      if (writer.length > 0) {
       $(".divBox").hide();
       $(".divExtra").empty();
       $(".divExtra").append(data);
      } else {
       $(".divBox").show();
       $(".divExtra").empty();
      }
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function search_y() {
  var year = document.getElementById("year").value;
  $.ajax({
    type: "POST",
    url: "search_y.php",
    data: {
      year: year
    },
    success: function(data) {
      if (year.length > 0) {
       $(".divBox").hide();
       $(".divExtra").empty();
       $(".divExtra").append(data);
      } else {
       $(".divBox").show();
       $(".divExtra").empty();
      }
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function search_flu() {
  $.ajax({
    type: "GET",
    url: "search_flu.php",
    success: function(data) {
       $(".divBox").hide();
       $(".divExtra").empty();
       $(".divExtra").append(data);
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function rank() {
  $.ajax({
    type: "GET",
    url: "rank.php",
    success: function(data) {
       $(".divBox").hide();
       $(".divExtra").empty();
       $(".divExtra").append(data);
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function message() {
  $.ajax({
    type: "GET",
    url: "message.php",
    success: function(data) {
       $(".divBox").hide();
       $(".divExtra").empty();
       $(".divExtra").append(data);
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function message_s() {
  var receiver_id = document.getElementById("receiver_id").value;
  var body = document.getElementById("body").value;
  $.ajax({
    type: "POST",
    url: "message_s.php",
    data: {
      receiver_id: receiver_id,
      body: body
    },
    success: function(data){
      alert("Your message has been successfully sent.");
      window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function cancel() {
  var writer = document.getElementById("writer");
  writer.value = "";
  var year = document.getElementById("year");
  year.value = "";
  $(".divBox").show();
  $(".divExtra").empty();
}

function twitt_w() {
  var body = document.getElementById("body").value;
  $.ajax({
    type: "POST",
    url: "twitt_w.php",
    data: {
      body: body
    },
    success: function(){
      window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function twitt_d(tid) {
  $.ajax({
    type: "POST",
    url: "twitt_d.php",
    data: {
      tid: tid
    },
    success: function(){
      window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function like(tid) {
  $.ajax({
    type: "POST",
    url: "like.php",
    data: {
      tid: tid
    },
    success: function(){
      window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function comment_w(tid) {
  var body = document.getElementById("body"+tid).value;
  $.ajax({
    type: "POST",
    url: "comment_w.php",
    data: {
      tid: tid,
      body: body
    },
    success: function(){
      window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function comment_d(cid) {
  $.ajax({
    type: "POST",
    url: "comment_d.php",
    data: {
      cid: cid
    },
    success: function(){
      window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}

function follow(uid) {
  $.ajax({
    type: "POST",
    url: "follow.php",
    data: {
      uid: uid
    },
    success: function(){
     window.location.reload();
    },
    error: function(){
      alert("An error occurred during processing your request.");
    }
  });
}