document.addEventListener("DOMContentLoaded", ()=>{
    let cont = document.getElementById('graf').getContext('2d');
    let graf = new Chart(cont, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'y1 = sin^2(ax)',
                    borderColor: 'red',
                    data: [],
                    fill:false,
                },
                {
                    label: 'y2 = cos^2(ax)',
                    borderColor: 'blue',
                    data: [],
                    fill:false,
                },
                {
                    label: 'y3 = sin(ax)*cos(ax)',
                    borderColor: 'green',
                    data: [],
                    fill:false,
                },
            ]
        }
    });
        let source = new EventSource('sse.php');
        source.addEventListener('message', function(e) {
                let data = JSON.parse(e.data);
                document.getElementById('vypis').innerHTML=e.data;
                graf.data.labels.push(data.x);
                graf.data.datasets[0].data.push({x: data.x, y: data.y1});
                graf.data.datasets[1].data.push({x: data.x, y: data.y2});
                graf.data.datasets[2].data.push({x: data.x, y: data.y3});
                graf.update();
        });
})
function kliknutie(){
    let hodnota=document.getElementById("hodnota").value;
    $.ajax({
        type: "post",
        url: "update.php",
        data:{
            hodnota:hodnota,
        },
        success:function (data) {

        }

    });
}
