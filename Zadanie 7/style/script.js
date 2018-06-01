
  function format_date(date)
  {
    return pad(date.getMonth()+1,2)+""+pad(date.getDate(),2);
  }


  var selected_date = (new Date());
  var selected_country = "sk";

  function pad(n, width, z) {
    z = z || "0";
    n = n + "";
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
  }

  function send_rest_req(app,data,callback,met="GET",dt={})
  {
    $.ajax({
      method: met,
      data: dt,
          headers:{  
       "Accept":"application/json",//depends on your api
        "Content-type":"application/x-www-form-urlencoded"//depends on your api
          },   url:"http://147.175.98.148/80331_chovanec_z6/api/krajina/"+selected_country+"/"+app+"/"+data,
          success:function(response, textStatus, xhr){
            try{
              callback(xhr.status,response);            
            }catch(err) {

            }
          },
          error: function(xhr, textStatus, errorThrown) {
            try{
              callback(xhr.status,errorThrown);
            }catch(err) {
              
            }
            }
      
        });
  }

  function search_nameday_call(status,data)
  {
    var out;
      switch(status){
         case 200: // OK
          {
            var prs = JSON.parse(data);
            out = ("<p>Dňa "+selected_date.getDate()+"."+(selected_date.getMonth()+1)+". májú meniny: <span>"+prs.meniny+"</span></p>");
            break;
          }
          case 404:{
            out = "<p>Pre zadanú krajinu neboli definované sviatky.</p>";
            break;
          }
           default:{
            out =("[ERROR] "+status);
          }
      }
      $("#out").html(out);

  }

  function search_sviatky_call(status,data)
  {
    var out;
      switch(status){
         case 200: // OK
          {
            var prs = JSON.parse(data);
            console.log(prs.sviatky);
            var out = "<table class='tablesorter'><thead><tr><th>Dátum</th><th>Sviatok</th></tr></thead><tbody>"
            for (var sv in prs.sviatky) {
              var dat = prs.sviatky[sv].den;
              var dy = dat.substring(2, 4);
              var mt = dat.substring(0, 2);
              var sva = prs.sviatky[sv].sviatok;
              console.log(sv);
              out += "<tr><td>"+dy+"."+mt+"</td><td>"+sva+"</td></tr>";
            }
            out += "</tbody></table>";
            break;
          }
          case 404:{
            out = "<p>Pre zadanú krajinu neboli definované sviatky.</p>";
            break;
          }
           default:{
            out =("[ERROR] "+status);
          }
         
      }
       $("#out").html(out);
  }

  function search_memorials_call(status,data)
  {
    var out = "";
      switch(status){
         case 200: // OK
          {
            var prs = JSON.parse(data);
            console.log(prs);
            var out = "<table class='tablesorter'><thead><tr><th>Dátum</th><th>Pamätný deň</th></tr></thead><tbody>"
            for (var sv in prs.pamatne) {
              var dat = prs.pamatne[sv].den;
              var dy = dat.substring(2, 4);
              var mt = dat.substring(0, 2);
              var sva = prs.pamatne[sv].sviatok;
              console.log(sv);
              out += "<tr><td>"+dy+"."+mt+"</td><td>"+sva+"</td></tr>";
            }
            out += "</tbody></table>";
            break;
          }
          case 404:{
            out = "<p>Pre zadanú krajinu neboli definované pamätné dni.</p>";
            break;
          }
           default:{
            out = ("[ERROR] "+status);
          }
      }
      $("#out").html(out);

  }

  function search_nameday_name_call(status,data)
  {
    var out = "";
      switch(status){
         case 200: // OK
          {
            var prs = JSON.parse(data);
              console.log(prs);
              var dat = prs.datum;
              var dy = dat.substring(2, 4);
              var mt = dat.substring(0, 2);
            out = ("<p>"+($("#srchname").val())+" ma meniny("+selected_country+") dňa "+dy+"."+mt+"</p>");
            break;
          }
          case 404:{
            out = "<p>Pre zadanú krajinu nebol nájdený dátum menín hladaného mena.</p>";
            break;
          }
           default:{
            out = ("[ERROR] "+status);
          }
      }
      $("#out").html(out);

  }
  function search_nameday_add_call(status,data)
  {
    var out = "";
    console.log(status);
      switch(status){
         case 201: // CREATED
          {
            out = ("<p>Meno bolo úspešne pridané</p>");
            break;
          }
          case 404:{
            out = "<p>Pre zadanú krajinu sa nepodarilo pridať meno.</p>";
            break;
          }
           default:{
            out = ("[ERROR] "+status);
          }
      }
      $("#out").html(out);

  }


$( document ).ready(function() {
      $("#named").on("click",function(){
          //search_nameday(new Date($("#date").val()))
          send_rest_req("meniny",format_date(selected_date),search_nameday_call);
        
    });

    $("#sviat").on("click",function(){
          //search_nameday(new Date($("#date").val()))
          send_rest_req("sviatky","all",search_sviatky_call);
        
    });

    $("#memor").on("click",function(){
          //search_nameday(new Date($("#date").val()))
          send_rest_req("pamatne","all",search_memorials_call);
        
    });

    $("#nmsrch").on("click",function(){
          //search_nameday(new Date($("#date").val()))
          var nm = $("#srchname").val();
          if(nm.length > 0)
          {
            send_rest_req("meno",nm,search_nameday_name_call);

          }else{
            // ERROR
          }
        
    });
    $("#addname").on("click",function(){
          //search_nameday(new Date($("#date").val()))
          var nm = $("#addnm");
          if(nm.val().length > 0)
          {
            send_rest_req("meniny",format_date(selected_date),search_nameday_add_call,"POST",{meno:nm.val()});
            nm.val("");
          }else{
            // ERROR
          }
        
    });


    var day = ("0" + selected_date.getDate()).slice(-2);
    var month = ("0" + (selected_date.getMonth() + 1)).slice(-2);

    var today = selected_date.getFullYear()+"-"+(month)+"-"+(day) ;
    $("#date").val(today);

 $("#date").bind("input", function() { 

    selected_date = new Date($(this).val());
});

  $("#country").bind("input", function() { 

    selected_country = $( "#country option:selected" ).val();
    console.log(selected_country);
});

});