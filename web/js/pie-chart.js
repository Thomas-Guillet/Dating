var data = [{
    value: 100,
  }, {
    value: 100,
  }, {
    value: 100,
  }, {
    value: 100,
  }, {
    value: 100,
  }, {
    value: 100,
  }];

  var total = 0;
  for (obj of data) {
    total += obj.value;
  }

  var canvas = document.getElementById('myCanvas');
  var ctx = canvas.getContext('2d');
  var previousRadian;
  var middle = {
    x: canvas.width / 2,
    y: canvas.height / 2,
    radius: canvas.height / 2,
  };

  ctx.lineWidth=3;

  for (obj of data) {
    previousRadian = previousRadian || 0;
    obj.percentage = parseInt((obj.value / total) * 100)

    ctx.beginPath();
    ctx.fillStyle = '#1e1d2f';
    obj.radian = (Math.PI * 2) * (obj.value / total);
    ctx.moveTo(middle.x, middle.y);
    ctx.arc(middle.x, middle.y, middle.radius - 3, previousRadian, previousRadian + obj.radian, false);
    ctx.closePath();
    ctx.fill();
    ctx.strokeStyle="#21213A";
    ctx.stroke();
    ctx.save();
    ctx.restore();

    previousRadian += obj.radian;
  }
