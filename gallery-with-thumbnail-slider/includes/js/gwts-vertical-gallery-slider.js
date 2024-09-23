jQuery(document).ready(function ($) {
  //vertical width
  var gwts_vrt_width = document.getElementById('gwts_vrt_width');
  var dispVerWidth = document.getElementById('gwts_displyVert_wdth');
  dispVerWidth.innerHTML = gwts_vrt_width.value;
  gwts_vrt_width.oninput = function(){
    dispVerWidth.innerHTML = this.value;
  }
  //vertical height
  var gwts_vrt_height = document.getElementById('gwts_vrt_height');
  var gwts_Vslide_height = document.getElementById('gwts_Vslide_height');
  gwts_Vslide_height.innerHTML = gwts_vrt_height.value;
  gwts_vrt_height.oninput = function(){
    gwts_Vslide_height.innerHTML = this.value;
  }
  //ThumbWidth
  var gwts_thmb_width = document.getElementById('gwts_thmb_width');
  var gwts_thumbVWidt = document.getElementById('gwts_thumbVWidt');
  gwts_thumbVWidt.innerHTML = gwts_thmb_width.value;
  gwts_thmb_width.oninput = function(){
    gwts_thumbVWidt.innerHTML = this.value;
  }
  //Show maximumThumb
  var gwts_max_thumb = document.getElementById('gwts_max_thumb');
  var gwts_show_maxthumb = document.getElementById('gwts_show_maxthumb');
  gwts_show_maxthumb.innerHTML = gwts_max_thumb.value;
  gwts_max_thumb.oninput = function(){
    gwts_show_maxthumb.innerHTML = this.value;
  }

  //breakpoints
  //breakpoints 480px height
  var gwts_brekvrt_height = document.getElementById('gwts_brekvrt_height');
  var gwts_Vslide_brkheight = document.getElementById('gwts_Vslide_brkheight');
  gwts_Vslide_brkheight.innerHTML = gwts_brekvrt_height.value;
  gwts_brekvrt_height.oninput = function(){
    gwts_Vslide_brkheight.innerHTML = this.value;
  }
  
  //breakpoints 480px thumbitems
  var gwts_max_brkthumb = document.getElementById('gwts_max_brkthumb');
  var gwts_show_brkmaxthumb = document.getElementById('gwts_show_brkmaxthumb');
  gwts_show_brkmaxthumb.innerHTML = gwts_max_brkthumb.value;
  gwts_max_brkthumb.oninput = function(){
    gwts_show_brkmaxthumb.innerHTML = this.value;
  }

  //breakpoints 641px height
  var gwts_vrt_sixfoheight = document.getElementById('gwts_vrt_sixfoheight');
  var gwts_Vslide_sixfouroneheight = document.getElementById('gwts_Vslide_sixfouroneheight');
  gwts_Vslide_sixfouroneheight.innerHTML = gwts_vrt_sixfoheight.value;
  gwts_vrt_sixfoheight.oninput = function(){
    gwts_Vslide_sixfouroneheight.innerHTML = this.value;
  }
  
  //breakpoints 641px thumbitems
  var gwts_max_sixforthumb = document.getElementById('gwts_max_sixforthumb');
  var gwts_show_sixfomaxthumb = document.getElementById('gwts_show_sixfomaxthumb');
  gwts_show_sixfomaxthumb.innerHTML = gwts_max_sixforthumb.value;
  gwts_max_sixforthumb.oninput = function(){
    gwts_show_sixfomaxthumb.innerHTML = this.value;
  }

  //breakpoints 800px height
  var gwts_vrt_eightheight = document.getElementById('gwts_vrt_eightheight');
  var gwts_Vslide_eightheight = document.getElementById('gwts_Vslide_eightheight');
  gwts_Vslide_eightheight.innerHTML = gwts_vrt_eightheight.value;
  gwts_vrt_eightheight.oninput = function(){
    gwts_Vslide_eightheight.innerHTML = this.value;
  }
  
  //breakpoints 800px thumbitems
  var gwts_max_eigthumb = document.getElementById('gwts_max_eigthumb');
  var gwts_show_eightmaxthumb = document.getElementById('gwts_show_eightmaxthumb');
  gwts_show_eightmaxthumb.innerHTML = gwts_max_eigthumb.value;
  gwts_max_eigthumb.oninput = function(){
    gwts_show_eightmaxthumb.innerHTML = this.value;
  }
});