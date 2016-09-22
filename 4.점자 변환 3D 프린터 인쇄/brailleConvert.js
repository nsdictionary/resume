var lastLnState = 0, 	// 0 :non, 1:english, 2:korean
	lastIsNum = false; 	// 수표 체크용 flag variable, lastLnState 와 섞이지않게 분리
	layerNum = 0;		// svg layer index number

//svg 동적 생성
var DrawBraille = function(){
	var svg = mksvg('svg', {
			'xmlns': 'http://www.w3.org/2000/svg', 'version': '1.1',
			'width': '100px', 'height': '100px',
			'viewBox': '0 0 100 100',
			'id': 'Layer_'+layerNum,
		}),
		fillDots = [
			mksvg('path', {d:"M24.124000000000002,23.979a6.978,6.978 0 1,0 13.956,0a6.978,6.978 0 1,0 -13.956,0"}),
			mksvg('path', {d:"M24.124000000000002,50a6.978,6.978 0 1,0 13.956,0a6.978,6.978 0 1,0 -13.956,0"}),
			mksvg('path', {d:"M24.124000000000002,76.02a6.978,6.978 0 1,0 13.956,0a6.978,6.978 0 1,0 -13.956,0"}),
			mksvg('path', {d:"M61.919999999999995,23.979a6.978,6.978 0 1,0 13.956,0a6.978,6.978 0 1,0 -13.956,0"}),
			mksvg('path', {d:"M61.919999999999995,50a6.978,6.978 0 1,0 13.956,0a6.978,6.978 0 1,0 -13.956,0"}),
			mksvg('path', {d:"M61.919999999999995,76.02a6.978,6.978 0 1,0 13.956,0a6.978,6.978 0 1,0 -13.956,0"})
/*
			mksvg('circle', {cx: 31.102, cy: 23.979, r:6.978} ),
			mksvg('circle', {cx: 31.102, cy: 50, r:6.978} ),
			mksvg('circle', {cx: 31.102, cy: 76.02, r:6.978} ),
			mksvg('circle', {cx: 68.898, cy: 23.979, r:6.978} ),
			mksvg('circle', {cx: 68.898, cy: 50, r:6.978} ),
			mksvg('circle', {cx: 68.898, cy: 76.02, r:6.978} )
*/
		],
		lineDots = [
			mksvg('circle', {stroke:"black", 'stroke-width':"1", fill: 'white', cx: 31.102, cy: 23.979, r:6.978} ),
			mksvg('circle', {stroke:"black", 'stroke-width':"1", fill: 'white', cx: 31.102, cy: 50, r:6.978} ),
			mksvg('circle', {stroke:"black", 'stroke-width':"1", fill: 'white', cx: 31.102, cy: 76.02, r:6.978} ),
			mksvg('circle', {stroke:"black", 'stroke-width':"1", fill: 'white', cx: 68.898, cy: 23.979, r:6.978} ),
			mksvg('circle', {stroke:"black", 'stroke-width':"1", fill: 'white', cx: 68.898, cy: 50, r:6.978} ),
			mksvg('circle', {stroke:"black", 'stroke-width':"1", fill: 'white', cx: 68.898, cy: 76.02, r:6.978} )
		]

		// public method
		this.draw = function(dots){
			for( var i=1 ;i<=6 ;i++ ){
				if( dots.indexOf(i) !== -1 ) svg.appendChild(fillDots[i-1]);
// 				else svg.appendChild(lineDots[i-1]);
			}
			document.getElementById('braille').appendChild(svg);
		}

		layerNum++;
}

// 점자 svg 세팅 함수
var setSVGBraille = function(ch, lnState){ //0 :non, 1:english, 2:korean, 3:number
	var brailleDots = {
			// 영문
			a: [1], b: [1,2], c: [1,4], d: [1,4,5], e: [1,5],
			f: [1,2,4], g: [1,2,4,5], h: [1,2,5], i: [2,4],
			j: [2,4,5], k: [1,3], l: [1,2,3], m: [1,3,4],
			n: [1,3,4,5], o: [1,3,5], p: [1,2,3,4], q: [1,2,3,4,5],
			r: [1,2,3,5], s: [2,3,4], t: [2,3,4,5], u: [1,3,6],
			v: [1,2,3,6], w: [2,4,5,6], x: [1,3,4,6],
			y: [1,3,4,5,6], z: [1,3,5,6],

			// 숫자
			'1': [1], '2': [1,2], '3': [1,4], '4': [1,4,5],
			'5': [1,5], '6': [1,2,4], '7': [1,2,4,5], '8': [1,2,5],
			'9': [2,4], '0': [2,4,5],

			// 특문
			' ': [], '.': [2,5,6], ',': [5], '?': [2,3,6], '!': [2,3,5],

			// 변환기호
			'Caps': [6], 'StartEn': [3,5,6],
			'EndEn': [2,5,6], 'Number': [3,4,5,6],

			// 한글
			'ㄱ': [4], 'ㄴ': [1,4], 'ㄷ': [2,4], 'ㄹ': [5],
			'ㅁ': [1,5], 'ㅂ': [4,5], 'ㅅ': [6], 'ㅇ': [2,3,5,6], //원래종성만 있음
			'ㅈ': [4,6], 'ㅊ': [5,6], 'ㅋ': [1,2,4], 'ㅌ': [1,2,5],
			'ㅍ': [1,4,5], 'ㅎ': [2,4,5], 'ㅏ': [1,2,6], 'ㅑ': [3,4,5],
			'ㅓ': [2,3,4], 'ㅕ': [1,5,6], 'ㅗ': [1,3,6], 'ㅛ': [1,4,6],
			'ㅜ': [1,4], 'ㅠ': [1,4,6], 'ㅡ': [2,4,6], 'ㅣ': [1,3,5],
			'ㅔ': [1,3,4,5], 'ㅐ': [1,2,3,5], 'ㅖ': [3,4], 'ㅒ': [[3,4,5],[1,2,3,5]],
			'ㅢ': [2,4,5,6], 'ㅘ': [1,2,3,6], 'ㅝ': [1,2,3,4], 'ㅚ': [1,3,4,5,6],
			'ㅟ': [[1,3,4],[1,2,3,5]], 'ㅙ': [[1,2,3,6],[1,2,3,5]],
			'ㅞ': [[1,2,3,4],[1,2,3,5]], 'ㄲ': [[4],[4]], 'ㄸ': [[2,4],[2,4]],
			'ㅃ': [[4,5],[4,5]], 'ㅆ': [[6],[6]], 'ㅉ': [[4,6],[4,6]],
			'ㄳ': [[4],[6]], 'ㄵ': [[1,4],[4,6]], 'ㄶ': [[1,4],[2,4,5]],
			'ㄺ': [[5],[4]], 'ㄻ': [[5],[1,5]], 'ㄼ': [[5],[4,5]], 'ㄽ': [[5],[6]],
			'ㄾ': [[5],[1,2,5]], 'ㄿ': [[5],[1,4,5]], 'ㅀ': [[5],[2,4,5]],
			'ㅄ': [[4,5],[6]]
		},
		// svg 부호 생성 및 그리기
		draw = function(dots){
			var drawBraille = new DrawBraille();
			drawBraille.draw(dots);
		}

	// 수표 체크
	if( lnState == 3 && !lastIsNum) draw(brailleDots['Number']);

	// 영문 닫기표
	if( lnState == 2 && lastLnState == 1 ) draw(brailleDots['EndEn']);
	else if(lnState = 1 && ( (ch>="a") && (ch<="z") || (ch>="A") && (ch<="Z") ) ){
		// 영문 시작표
		if(lastLnState == 2 || lastLnState == 0 ) draw(brailleDots['StartEn']);

		// 영문 대문자
		if( ch != ch.toLowerCase() ){
			draw(brailleDots['Caps']);
			ch = ch.toLowerCase();
		}
	}

	// 단일기호와 연속기호 분기
	if( Array.isArray(brailleDots[ch][0]) ){
		var len = brailleDots[ch].length-1;
		for( var i=0; i<=len; i++ ) draw(brailleDots[ch][i]);
	}else draw(brailleDots[ch]);
}

// svg 엘리먼트 생성
var mksvg = function( tag, attrs ){
    var el= document.createElementNS( 'http://www.w3.org/2000/svg', tag );
    for( var k in attrs ) el.setAttribute(k, attrs[k]);
    return el;
}

// 한글 자소분리
var hangulToJaso = function(text){
	var ChoSeong = new Array( 0x3131, 0x3132, 0x3134, 0x3137, 0x3138,
			0x3139, 0x3141, 0x3142, 0x3143, 0x3145, 0x3146, 0x3147, 0x3148,
			0x3149, 0x314a, 0x314b, 0x314c, 0x314d, 0x314e );
	var JungSeong = new Array( 0x314f, 0x3150, 0x3151, 0x3152, 0x3153,
			0x3154, 0x3155, 0x3156, 0x3157, 0x3158, 0x3159, 0x315a, 0x315b,
			0x315c, 0x315d, 0x315e, 0x315f, 0x3160, 0x3161, 0x3162, 0x3163 );
	var JongSeong = new Array( 0x0000, 0x3131, 0x3132, 0x3133, 0x3134,
			0x3135, 0x3136, 0x3137, 0x3139, 0x313a, 0x313b, 0x313c, 0x313d,
			0x313e, 0x313f, 0x3140, 0x3141, 0x3142, 0x3144, 0x3145, 0x3146,
			0x3147, 0x3148, 0x314a, 0x314b, 0x314c, 0x314d, 0x314e );
	var chars = new Array()
	var v = new Array();

	for( var i=0; i<text.length; i++ ){
		chars[i] = text.charCodeAt(i);
		if( chars[i] >= 0xAC00 && chars[i] <= 0xD7A3 ){
			var i1, i2, i3;
			i3 = chars[i] - 0xAC00;
			i1 = i3 / (21 * 28);
			i3 = i3 % (21 * 28);
			i2 = i3 / 28;
			i3 = i3 % 28;
			v.push( String.fromCharCode( ChoSeong[parseInt(i1)] ) );
			v.push( String.fromCharCode( JungSeong[parseInt(i2)] ) );
			if( i3 != 0x0000 ) v.push( String.fromCharCode( JongSeong[parseInt(i3)] ) );
		}else{
			v.push( String.fromCharCode(chars[i]) );
		}
	}
	return v;
}

// 분리된 자소의 한영구분을 위해 바이트 체크
var chByteCheck = function(ch){
	var chByte = 1;
	ch = escape(ch);
	if( ch.substring(0, 2) == '%u' ) chByte = ch.substring(2,4) == '00' ? 1 : 2;
	else if( ch.substring(0, 1) == '%') chStr = parseInt( ch.substring(1, 3), 16 ) > 127 ? 2 : 1;
	return chByte;
}

// 입력받은 문자열을 분리해서 배열로 리턴
var divideHangul = function(str){
	var len = str.length,
		v = [];
	for( var i=0; i<len; i++ ){
		v.push( chByteCheck(str[i]) == 2 ? hangulToJaso(str[i]) : str[i] );
	}
	return v;
}

// 점자 변환 함수
var convert = function(str){
	$('#braille').empty();
	layerNum = 0;

	var data = divideHangul(str),
	isNumber = function(str){
		var pattern = /^\d+$/;
		return pattern.test(str);  // returns a boolean
	},
	isEn = function(str){
		var pattern = /^[a-zA-Z]+$/;
		return pattern.test(str);
	}
 	console.log(data); //디버그용

	for( var i=0; i<data.length; i++ ){
		if( isNumber(data[i]) ){
			setSVGBraille(data[i], 3);
			lastIsNum = true;
			continue;
		}

		if( typeof data[i] != 'object' && isEn(data[i]) ){ // 영문
			setSVGBraille(data[i], 1);
			lastLnState = 1;
		}else{ // 한글
			var len = data[i].length;
			for( var j=0; j<len; j++ ){
				var v = data[i][j];
				setSVGBraille(v, 2);
				lastLnState = 2;
			}
		}
		lastIsNum = false;
	}

	$("#braille").css('background-color', '#ccc');

/*
	var viewerSettings = {
		cameraEyePosition : [-3, -2, 1.0],
		cameraCenterPosition : [0.0, 0.0, 0.0],
		cameraUpVector : [0.0, 0.0, 1.0],
		nearClippingPlane : 1.0,
		farClippingPlane : 100000.0
	};

	var viewer = new JSM.ThreeViewer ();
	if( !viewer.Start( document.getElementById('svgcanvas'), viewerSettings ) ){
		viewer.RemoveMeshes();
		viewer = null;
			return;
		}

		var svgObject = document.getElementById('Layer_0');
		var modelAndMaterials = JSM.SvgToModel(svgObject, 8, 5);
		var model = modelAndMaterials[0];
		var materials = modelAndMaterials[1];
		var meshes = JSM.ConvertModelToThreeMeshes(model, materials);
		viewer.AddMeshes(meshes);

	// 	console.log( generateSTL(meshes[0].geometry) );
	// 	saveSTL( meshes[0].geometry );

		viewer.FitInWindow ();
		viewer.Draw();
	*/

	}

	var svgToImg = function(){
		var svgElements= $("#braille").find('svg');
		if( svgElements.length == 0 ){
			alert('변환을 한뒤에 시도해 주세요.');
			return;
		}
		showLd();

		svgElements.each( function(){
		    var canvas, xml;
		    canvas = document.createElement("canvas");
		    canvas.className = "screenShotTempCanvas";
		    xml = (new XMLSerializer()).serializeToString(this);
		    xml = xml.replace(/xmlns=\"http:\/\/www\.w3\.org\/2000\/svg\"/, '');

		    canvg(canvas, xml);
		    $(canvas).insertAfter(this);

		    this.className = "tempHide";
		    $(this).hide();
		});

		html2canvas(document.getElementById('braille'), {
			onrendered: function(canvas) {
// 			document.body.appendChild(canvas); hideLd(); return;

			$('#braille').empty();
			$('#user_string').val('');


			var dataURL = canvas.toDataURL();
			$.ajax({
				type: "POST",
				url: "upload.php",
				data: {imgBase64: dataURL},
			}).done(function(o){
				console.log(o);
				alert('업로드 성공');
			});

// 			var snapshotPNG = canvas.toDataURL();
//             window.open(snapshotPNG, 'snapshot', 'width=300, height=300');

            hideLd();
		}
	});
}

var stringifyVector = function(vec){return ""+vec.x+" "+vec.y+" "+vec.z;}

var stringifyVertex = function(vec){return "vertex "+stringifyVector(vec)+" \n";}

var generateSTL = function(geometry){
	var vertices = geometry.vertices,
		tris = geometry.faces,
		stl = "solid pixel";

	for( var i = 0; i<tris.length; i++ ){
		stl += ( "facet normal " + stringifyVector(tris[i].normal) + " \n" );
		stl += ("outer loop \n");
		stl += stringifyVertex( vertices[ tris[i].a ] );
		stl += stringifyVertex( vertices[ tris[i].b ] );
		stl += stringifyVertex( vertices[ tris[i].c ] );
		stl += ("endloop \n");
		stl += ("endfacet \n");
	}
	stl += ("endsolid");
	return stl
}

var saveSTL = function(geometry){
	var stlString = generateSTL(geometry);
	var blob = new Blob( [stlString], {type: 'text/plain'} );
	saveAs( blob, 'pixel_printer.stl' );
}