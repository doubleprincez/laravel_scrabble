const container = document.getElementById("container");
const letterContainer = document.getElementById("letterContainer");
class Board {
  constructor() {
    this.reset()
  } // End of constructor

  reset(){
    this.grid = [
    [4, 0, 0, 1, 0, 0, 0, 4, 0, 0, 0, 1, 0, 0, 4],
    [0, 2, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 2, 0],
    [0, 0, 2, 0, 0, 0, 1, 0, 1, 0, 0, 0, 2, 0, 0],
    [1, 0, 0, 2, 0, 0, 0, 1, 0, 0, 0, 2, 0, 0, 1],
    [0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0],
    [0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0],
    [0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 0, 0],
    [4, 0, 0, 1, 0, 0, 0, 5, 0, 0, 0, 1, 0, 0, 4],
    [0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 0, 0],
    [0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0],
    [0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0],
    [1, 0, 0, 2, 0, 0, 0, 1, 0, 0, 0, 2, 0, 0, 1],
    [0, 0, 2, 0, 0, 0, 1, 0, 1, 0, 0, 0, 2, 0, 0],
    [0, 2, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 2, 0],
    [4, 0, 0, 1, 0, 0, 0, 4, 0, 0, 0, 1, 0, 0, 4]
  ];
    this.letterGrid = [];
    for (var i = 1; i <= 10; i++) {
      this.letterGrid.push(["","","","","","","","","",""])
    }
  }

}
class Tile {
  constructor(letter,value) {
    this.letter = letter;
    this.value = value;
    this.position = "bag";
    this.row = -1;
    this.col = -1;
  }
}
class Bag {
  constructor() {
    this.refill();
  }
  refill() {
    this.letters = {
    A: [9, 1],
    B: [2, 3],
    C: [2, 3],
    D: [4, 2],
    E: [12, 1],
    F: [2, 4],
    G: [3, 2],
    H: [2, 4],
    I: [9, 1],
    J: [1, 8],
    K: [1, 5],
    L: [4, 1],
    M: [2, 3],
    N: [6, 1],
    O: [8, 1],
    P: [2, 3],
    Q: [1, 10],
    R: [6, 1],
    S: [4, 1],
    T: [6, 1],
    U: [4, 1],
    V: [2, 4],
    W: [2, 4],
    X: [1, 8],
    Y: [2, 4],
    Z: [1, 10],
    "*": [2, 0]
    };
  }
  pickLetter() {
    var sumWeight = 0
  for (var i in this.letters) {
    sumWeight += this.letters[i][0]
  }
  for (i = 1; i <= 7; i++) {
    var randomWeightValue = Math.random() *sumWeight << 0;
    for ( var letter in this.letters) {
      var letterWeight = this.letters[letter][0]
      var score = this.letters[letter][1]
      if (randomWeightValue - letterWeight <0) {
        break;
      } else {
        randomWeightValue -= letterWeight;
      }
    }
    sumWeight--;
    if (letterWeight == 1) {
      delete this.letters[letter];
    } else {
      this.letters[letter][0]--;
    }
    return letter;
    // let letter = document.createElement("DIV");
    // letter.className = "letter";
    // letter.innerHTML = el + "<sub>" + score + "</sub>";
    // letterContainer.insertBefore(letter, letterContainer.childNodes[2]);
}
  }
}
class Holder {
  constructor(name) {
    this.name = name;
    this.letters = [];
    this.holderSize = 7;
  }
  empty(){
    this.letters = [];
  }
  addLetter(letter){
    if(this.letters.length < this.holderSize){
      this.letters.push(letter)
    }
  }
  display() {
  for (var i = 0; i <= 6; i++) {
    let letter = document.createElement("DIV");
    letter.className = "letter";
    var score = 1;
    letter.innerHTML = this.letters[i] + "<sub>" + score + "</sub>";
    letterContainer.insertBefore(letter, letterContainer.childNodes[2]);
  }
}



}



//Main code starts here
board = new Board();
bag = new Bag();
holder1 = new Holder("Player 1");
holder2 = new Holder("Player 2");
// create all the files
for (var i = 1; i <= 7; i++) {
  var letter;
  letter = bag.pickLetter();
  holder1.addLetter(letter);
}
// console.log(holder1.letters);
for (var i = 1; i <= holder1.letters.length; i++) {
  var letter, score;
  letter, score = bag.pickLetter()[0];
  holder2.addLetter(letter);
}

holder1.display();









//Old Code...

var v = 55;
var oldSliderValue = 0;
var globalSnapTimer = null;

function makeTable(rows) {
  var cols = rows;
  document.body.style.setProperty("--grid-rows", rows);
  document.body.style.setProperty("--grid-cols", cols);
  var grid = [
    [4, 0, 0, 1, 0, 0, 0, 4, 0, 0, 0, 1, 0, 0, 4],
    [0, 2, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 2, 0],
    [0, 0, 2, 0, 0, 0, 1, 0, 1, 0, 0, 0, 2, 0, 0],
    [1, 0, 0, 2, 0, 0, 0, 1, 0, 0, 0, 2, 0, 0, 1],
    [0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0],
    [0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0],
    [0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 0, 0],
    [4, 0, 0, 1, 0, 0, 0, 5, 0, 0, 0, 1, 0, 0, 4],
    [0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 0, 0],
    [0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 3, 0],
    [0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0],
    [1, 0, 0, 2, 0, 0, 0, 1, 0, 0, 0, 2, 0, 0, 1],
    [0, 0, 2, 0, 0, 0, 1, 0, 1, 0, 0, 0, 2, 0, 0],
    [0, 2, 0, 0, 0, 3, 0, 0, 0, 3, 0, 0, 0, 2, 0],
    [4, 0, 0, 1, 0, 0, 0, 4, 0, 0, 0, 1, 0, 0, 4]
  ];

  for (c = 0; c < rows * cols; c++) {
    let cell = document.createElement("div");

    if (grid[Math.floor(c / 15)][c % 15] == 4) {
      cell.className = "multiplier";
      cell.innerHTML = "Triple word";
      cell.style.backgroundColor = "#F5654A";
    } else if (grid[Math.floor(c / 15)][c % 15] == 2) {
      cell.className = "multiplier";
      cell.innerHTML = "<sub>Double</sub> word";
      cell.style.backgroundColor = "#F9BBAC";
    } else if (grid[Math.floor(c / 15)][c % 15] == 3) {
      cell.className = "multiplier";
      cell.innerHTML = "Triple letter";
      cell.style.backgroundColor = "#3597B0";
    } else if (grid[Math.floor(c / 15)][c % 15] == 1) {
      cell.className = "multiplier";
      cell.innerHTML = "<sub>Double</sub> letter";
      cell.style.backgroundColor = "#B8CDC8";
    } else if (grid[Math.floor(c / 15)][c % 15] == 5) {
      let star = document.createElement("IMG");
      star.id = "star";
      cell.className = "multiplier";
      star.setAttribute(
        "src",
        "https://upload.wikimedia.org/wikipedia/commons/7/78/BlackStar.PNG"
      );
      star.setAttribute("draggable", false);
      cell.style.backgroundColor = "#F9BBAC";
      cell.appendChild(star);
    }
    container.appendChild(cell).className += " grid-item";
  }
}

makeTable(15);

function toggleFullscreen() {
  if (
    !document.fullscreenElement && // alternative standard method
    !document.mozFullScreenElement &&
    !document.webkitFullscreenElement
  ) {
    // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(
        Element.ALLOW_KEYBOARD_INPUT
      );
    }
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  }
}
$("#usericons").click(function() {
    $(this).find('img').toggle();
});
$("#roboticons").click(function() {
    $(this).find('img').toggle();
});
$(document).bind(
  "webkitfullscreenchange mozfullscreenchange fullscreenchange",
  function(e) {
    var state =
      document.fullScreen ||
      document.mozFullScreen ||
      document.webkitIsFullScreen;
    if (state) {
      document.getElementById("fullscreen").src =
        "https://www.flaticon.com/svg/static/icons/svg/240/240058.svg";
    } else {
      document.getElementById("fullscreen").src =
        "https://www.flaticon.com/svg/static/icons/svg/248/248365.svg";
    }
  }
);

var letters = {
  A: [9, 1],
  B: [2, 3],
  C: [2, 3],
  D: [4, 2],
  E: [12, 1],
  F: [2, 4],
  G: [3, 2],
  H: [2, 4],
  I: [9, 1],
  J: [1, 8],
  K: [1, 5],
  L: [4, 1],
  M: [2, 3],
  N: [6, 1],
  O: [8, 1],
  P: [2, 3],
  Q: [1, 10],
  R: [6, 1],
  S: [4, 1],
  T: [6, 1],
  U: [4, 1],
  V: [2, 4],
  W: [2, 4],
  X: [1, 8],
  Y: [2, 4],
  Z: [1, 10],
  "*": [2, 0]
};

function makeLetters() {
  var sum = 0
  for (var e in letters) {
    sum += letters[e][0]
  }
  for (l = 1; l <= 7; l++) {
    var r = Math.random() *sum << 0;
    for ( var el in letters) {
      var amount = letters[el][0]
      var score = letters[el][1]
      if (r - amount <0) {
        break;
      } else {
        r-=amount;
      }
    }
    sum--;
    if (amount == 1) {
      delete letters[el];
    } else {
      letters[el][0]--;
    }
    let letter = document.createElement("DIV");
    letter.className = "letter";
    letter.innerHTML = el + "<sub>" + score + "</sub>";
    letterContainer.insertBefore(letter, letterContainer.childNodes[2]);

  }
}
//makeLetters();
function sliderEventHandler(event,ui){
  $("#amount").val(ui.value);
  v = $(this).slider("value");
  $(this)
    .find(".ui-slider-handle")
    .text(ui.value);
  document.documentElement.style.setProperty("--grid-scale", v);
  setSnap()
}

$(function() {
  $(".letter").draggable({
    snap: "#container .grid-item",
    // containment: "window",
    obstacle: ".obstacle",
    preventCollision: true,
    scroll: true,
    snapMode: "inner",
    // scrollSpeed: 100,
    snapTolerance: 29,
    cursorAt: {left:5,top:5},
    revert: "invalid",
    revertDuration: 300,
    cursor: "move",
    create: function( event, ui ) {
      setSnap()
    },
    start: function(event, ui) {
      $(this).css("box-shadow", "5px 5px 5px grey");
      $(this).css("margin", "0px");
      // $(this).css("margin-bottom", "0px");
      $(this).css("z-index", 2);
    },
    drag: function(event,ui) {
      var snapTolerance = $(this).draggable('option', 'snapTolerance');
    },
    stop: function(event, ui) {
      $(this).css("box-shadow", "none");
      $(this).css("z-index", 1);
      $(this).css("margin", "0px");
      // $(this).css("margin-bottom", "0px");
    }
  });

  $("#container .grid-item").droppable({
    drop: function(event, ui) {
      // alert($(this).index())
      if ($(this).css("background-color") != "rgb(217, 179, 130)") {
        ui.draggable.css('border-color', $(this).css('background-color'));
      }
      $(this).css("fontSize",0);
      $(this).children('*').css("display","none");
      ui.draggable.draggable("option", "disabled", true).css("opacity", "1");
      ui.draggable.addClass('obstacle').removeClass('letter');
      ui.draggable.appendTo($(this));
      ui.draggable.css({'top': 0, 'left' : 0})

    }
  });
  $(".letter").sortable({
    tolerance: "pointer"
  });

  $("#slider").slider({
    range: "max",
    orientation: "vertical",
    min: 40,
    max: 80,
    value: 50,
    step: 1,
    animate: "slow",
    slide: sliderEventHandler,
    change: sliderEventHandler,
    stop: sliderEventHandler,
    create: function(event, ui) {
      v = $(this).slider("value");
      $(this)
        .find(".ui-slider-handle")
        .text(v);
      document.documentElement.style.setProperty("--grid-scale", v);
    }
  }),
  $(".ui-slider").css("background", "#FFFFF0");
  $(".ui-slider-range-min").css("background", "#FFE1C4");

  if (window.matchMedia('(max-width: 850px)').matches) {
    $( "#slider" ).slider("value", 80);
  }
  setSnap();
});


$(window).resize(function() {
  if (window.matchMedia('(max-width: 850px)').matches) {
    document.getElementById("slider").style.display = "none";
  } else {
    document.getElementById("slider").style.display = "block";
  };
  if(globalSnapTimer != null) window.clearTimeout(globalSnapTimer);
  globalSnapTimer = window.setTimeout(function() {
    setSnap();
  }, 200);
});



function setSnap() {

  // snapTolerance = $( ".grid-item" ).width()/0.9/2 << 0 +1;
  $( ".letter" ).draggable( "option", "snapTolerance", $( ".letter" ).width()/0.9/2 << 0 +1);
  // $( ".letter" ).draggable("option", "cursorAt", {
  //   left: Math.floor($( ".letter" ).width()/2 ),
  //   top: Math.floor($( ".letter" ).width()/2)
  // });
//   0.9 for grid gap and /2 to half distance for snap.
}
// https://stackoverflow.com/questions/5735270/revert-a-jquery-draggable-object-back-to-its-original-container-on-out-event-of


var modal = document.getElementById("modal");
var scoresButton = document.getElementById("scores");
var accountButton = document.getElementById("account");
var optionButton = document.getElementById("options");
var closeModalButton = document.getElementById("closeModal");

scoresButton.onclick = function() {
  modal.style.display = "block";
  openTab(event, 'modalScores', 'tabcontent', 'modalTabLinks')
  document.getElementById("modalTab1").className += " active";
}
accountButton.onclick = function() {
  modal.style.display = "block";
  openTab(event, 'modalAccount', 'tabcontent', 'modalTabLinks')
  document.getElementById("modalTab2").className += " active";
}

optionButton.onclick = function() {
  modal.style.display = "block";
  openTab(event, 'modalOptions', 'tabcontent', 'modalTabLinks')
  document.getElementById("modalTab3").className += " active";
}


closeModalButton.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";

  }
}

function openTab(evt, section, content, links) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName(content);
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName(links);
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(section).style.display = "block";
  evt.currentTarget.className += " active";
}