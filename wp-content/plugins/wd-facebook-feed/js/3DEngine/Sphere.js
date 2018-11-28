var Sphere = function (radius, sides, numOfItems){
  
	for (var j = sides ; j > 0; j--){	
		for (var i = numOfItems / sides ; i > 0; i--)
		{
      var angle = i * Math.PI / (numOfItems / sides + 1);
      var angleB = j * Math.PI * 2 / (sides);
			
      var x =   Math.sin(angleB) * Math.sin(angle) * radius;
			var y =  Math.cos(angleB) * Math.sin(angle) * radius;
			var z =  Math.cos(angle) * radius;
      
      this.pointsArray.push(this.make3DPoint(x,y,z));
		}	
	};
};

Sphere.prototype = new DisplayObject3D();
