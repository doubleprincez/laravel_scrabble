var // format value as pixels
    px = function(v) { return v ? (v|0) + "px" : '0'; },
    
    r = Math.random,
    // random value [0,m)
    rv = function(m) { return m*r(); },
    // random value [n,m)
    rb = function(n,m) { return rv(m-n)+n; },
    // random value centered around 0
    rc0 = function(rng) { return (r()*rng)-(rng/2); },
    // random pixel value [0,m)
    rpx = function(m) { return px(rv(m)) },
    // random background position
    bkg = function(w,h) { return rpx(w)+" "+rpx(h||w); },
    
    // Prototypes
    fp = Function.prototype,
    ap = Array.prototype,
    dp = Document.prototype,
   	
    // callable forEach
    forEach = fp.call.bind(ap.forEach),
    // jQuery-ish
    $ = dp.querySelectorAll.bind(document),
    
    tiles = $(".tile"),
    rackTiles = $(".rack>.tile");

// Randomize the wood grain
forEach(tiles, function(el) {
  el.style.backgroundPosition = bkg(600);
});

// randomize the rotation of the rack tiles. 
forEach(rackTiles, function(el) {
  el.style.transform = "rotate("+ rc0(5) +"deg)";
});

// randomize the background-color of the all the tiles
forEach(tiles, function(el) {
	var s = el.style, cs = window.getComputedStyle(el), amt = r();
  s.backgroundColor = tinycolor(cs.backgroundColor)
    .darken(amt*20)
  	.desaturate(amt*30)
    .toHexString();
});
