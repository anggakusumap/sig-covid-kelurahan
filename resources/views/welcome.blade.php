@extends('layout.master')

@section('title','Covid-19 Bali')
@section('content')
<div class="row mt-3 mb-3">
  <div class="col-1 mr-3">
    <img src="./img/logo-bali.png" alt="Bali" style="width: 100px;">
  </div>
  <div class="col-4 mt-2">
    <h4>Pendataan Covid-19 di Bali <br> Tanggal {{$tanggal_saat_ini}}</h4>
  </div>
  <div class="col-6">
    <h5>Pilih Tanggal</h5>
    <form action="/search" method="POST">
      @csrf
      <div class="input-group">
        <input id="tanggalSearch" type="date" @if(isset($tanggal)) value="{{$tanggal}}" @endif name="tanggal"
          class="form-control" required>
        <span class="input-group-btn">
          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
        </span>
      </div>
    </form>
  </div>
</div>

<!-- Jumlah BOX -->
<div class="row mt-1">
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="info-box">
      <span class="info-box-icon bg-danger"><i class="fas fa-ambulance"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Positif</span>
          <div class="inner">
            <h3>{{$jumlah_positif[0]->total}} <sup style="font-size: 20px" ></sup></h3>
          </div>
        </div>  
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-success"><i class="fas fa-blind"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Sembuh</span>
            <div class="inner">
            <h3>{{$jumlah_sembuh[0]->sembuh}} <sup style="font-size: 20px"></sup></h3>
            </div>
          </div>  
      </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-wheelchair"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Dirawat</span>
            <div class="inner">
            <h3>{{$jumlah_dirawat[0]->perawatan}} <sup style="font-size: 20px"></sup></h3>
            </div>
          </div>  
      </div>
    </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-truck"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Meninggal</span>
          <div class="inner">
          <h3>{{$jumlah_meninggal[0]->meninggal}} <sup style="font-size: 20px"></sup></h3>
          </div>
        </div>  
    </div>
  </div>
</div>

<div class="row mt-2">
<div class="col-6">

<div class="card card-navy">
  <div class="card-header">
    <h3 class="card-title">Data Sebaran Kasus Covid-19 Provinsi Bali sampai tanggal <strong>{{$tanggal_saat_ini}}</strong></h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body table-responsive p-0">
    <table class="table table-hover text-nowrap">
      <thead>
        <tr>
          <th>No</th>
          <th>Kabupaten</th>
          <th>Positif</th>
          <th>Meninggal</th>
          <th>Sembuh</th>
          <th>Dirawat</th>
          {{-- <th>Tanggal</th> --}}
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $item)
        <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{ucfirst($item->kabupaten)}}</td>
          <td>{{$item->total}}</td>
          <td>{{$item->meninggal}}</td>
          <td>{{$item->sembuh}}</td>
          <td>{{$item->perawatan}}</td>
          {{-- <td>{{$item->tanggal}}</td> --}}
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
</div>

  <div class="col-6">

    <div class="card card-navy">
      <div class="card-header">
        <h3 class="card-title">Peta Sebaran Kasus Covid-19 sampai tanggal <strong>{{$tanggal_saat_ini}}</strong></h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body no-padding p-0">
        <div class="row">
          <div class="col-12">
            <div class="pad">
              <div id="mapid" style="height: 500px"></div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.card-body -->
      {{-- <div class="card-footer" style="background: white">
        <div class="row">
          <div class="col-6">
            <p>Color Start:</p>
            <input type="color" value="#edff6b" class="form-control" id="colorStart">
          </div>
          <div class="col-6">
            <p>Color End:</p>
            <input type="color" value="#6b6a01" class="form-control" id="colorEnd">
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-12">
            <button class="btn btn-primary form-control" id="btnGenerateColor">Generate Color</button>
          </div>

        </div>
      </div> --}}
    </div>
    <!-- /.card -->
  </div>
</div>

@endsection
@section("js")
<script src="https://unpkg.com/leaflet-kmz@latest/dist/leaflet-kmz.js"></script>
<script src="https://pendataan.baliprov.go.id/assets/frontend/map/leaflet.markercluster-src.js"></script>
<script src="http://leaflet.github.io/Leaflet.label/leaflet.label.js" charset="utf-8"></script>
<script>
  $(document).ready(function () {
    var dataMap=null;
    var dataColor=null;
    var colorMap=[ "9DCEFF", "89BCEF", "75AADF", "6298CF", "4E87BF", "3A75AF", "27639F", "13518F", "004080"
    ];
    var tanggal = $('#tanggalSearch').val();
    $.ajax({
      async:false,
      url:'getDataMap',
      type:'get',
      dataType:'json',
      data:{date: tanggal},
      success: function(response){
        dataMap = response["dataMap"];
        dataColor = response["dataColor"];
      }
    });
    console.log(dataMap);
    var map = L.map('mapid',{
      fullscreenControl:true,
    });
    
    $('#btnGenerateColor').on('click',function(e){
      var colorStart = $('#colorStart').val();
      var colorEnd = $('#colorEnd').val();
      $.ajax({
        async:false,
        url:'/create-pallete',
        type:'get',
        dataType:'json',
        data:{start: colorStart, end:colorEnd},
        success: function(response){
          colorMap = response;
          setMapAttr();
        }
      });
      
    });
    
    map.setView(new L.LatLng(-8.393807, 115.154153),9);
    var open_map = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 20,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1Ijoid2lkaWFuYXB3IiwiYSI6ImNrNm95c2pydjFnbWczbHBibGNtMDNoZzMifQ.kHoE5-gMwNgEDCrJQ3fqkQ',
        }).addTo(map);
    open_map.addTo(map);
    var defStyle = {opacity:'1',color:'#000000',fillOpacity:'0',fillColor:'#CCCCCC'};
    setMapAttr();
    function setMapAttr(){
      var icon_marker = L.icon({
        iconUrl: '/img/marker.png',
        iconSize: [40, 40],
      });
      
      var kmzParser = new L.KMZParser({
          
          onKMZLoaded: function (kmz_layer, name) {
            
              control.addOverlay(kmz_layer, name);
              var cluster_markers = L.markerClusterGroup();
              var layers = kmz_layer.getLayers()[0].getLayers();
              console.log(layers[0]);
              layers.forEach(function(layer, index){
                var kab  = layer.feature.properties.NAME_2;
                var kec =  layer.feature.properties.NAME_3;
                var kel = layer.feature.properties.NAME_4;
                var data;
              
                var STYLE = {opacity:'1',color:'#000',fillOpacity:'1'};
                var hijau_muda = {opacity:'1',color:'#000',fillOpacity:'1', fillColor:'#ADFF2F'};
                var hijau_tua = {opacity:'1',color:'#000',fillOpacity:'1', fillColor:'#006400'};
                var kuning = {opacity:'1',color:'#000',fillOpacity:'1', fillColor:'#ffff31'};
                var merah_muda = {opacity:'1',color:'#000',fillOpacity:'1', fillColor:'#8b0000'};
                var merah_tua = {opacity:'1',color:'#000',fillOpacity:'1', fillColor:'#ff355e'};
                if(!Array.isArray(dataMap) || !dataMap.length == 0){
                    var searchResult = dataMap.filter(function(it){
                      return it.kecamatan.replace(/\s/g,'').toLowerCase() === kec.replace(/\s/g,'').toLowerCase() &&
                              it.kelurahan.replace(/\s/g,'').toLowerCase() === kel.replace(/\s/g,'').toLowerCase();
                    });
                    if(!Array.isArray(searchResult) || !searchResult.length ==0){
                      var item = searchResult[0];
                      if(item.total == 0 ){
                        layer.setStyle(hijau_muda);  
                      }else if(item.perawatan == 0 && item.total>0 && item.sembuh >= 0 && item.meninggal >=0){
                        layer.setStyle(hijau_tua);
                      }else if(item.ppln ==1 && item.perawatan == 1 && item.total == 1 && item.tl==0 || item.ppdn ==1 && item.perawatan == 1 && item.total == 1 && item.tl==0){
                        layer.setStyle(kuning);
                      }else if((item.ppln >1 && item.perawatan <= item.ppln && item.sembuh <= item.ppln && item.tl == 0) || (item.ppdn >1 && item.perawatan <= item.ppdn && item.sembuh <= item.ppdn && item.tl == 0)  ){
                        layer.setStyle(merah_muda);
                      }else{
                        layer.setStyle(merah_tua);
                      }
                      data = '<table width="300">';
                      data +='  <tr>';
                      data +='    <th colspan="2">Keterangan</th>';
                      data +='  </tr>';
                    
                      data +='  <tr>';
                      data +='    <td>Kabupaten</td>';
                      data +='    <td>: '+kab+'</td>';
                      data +='  </tr>';              
      
                      data +='  <tr >';
                      data +='    <td>Kecamatan</td>';
                      data +='    <td>: '+kec+'</td>';
                      data +='  </tr>';

                      data +='  <tr>';
                      data +='    <td>Kelurahan</td>';
                      data +='    <td>: '+kel+'</td>';
                      data +='  </tr>';

                      data +='  <tr>';
                      data +='    <td>PP-LN</td>';
                      data +='    <td>: '+item.ppln+'</td>';
                      data +='  </tr>';

                      data +='  <tr>';
                      data +='    <td>PP-DN</td>';
                      data +='    <td>: '+item.ppdn+'</td>';
                      data +='  </tr>';

                      data +='  <tr>';
                      data +='    <td>TL</td>';
                      data +='    <td>: '+item.tl+'</td>';
                      data +='  </tr>';

                      data +='  <tr>';
                      data +='    <td>Lainnya</td>';
                      data +='    <td>: '+item.lainnya+'</td>';
                      data +='  </tr>';

                      data +='  <tr style="color:green">';
                      data +='    <td>Sembuh</td>';
                      data +='    <td>: '+item.sembuh+'</td>';
                      data +='  </tr>';

                      data +='  <tr style="color:blue">';
                      data +='    <td>Dalam Perawatan</td>';
                      data +='    <td>: '+item.perawatan+'</td>';
                      data +='  </tr>';

                      data +='  <tr style="color:red">';
                      data +='    <td>Meninggal</td>';
                      data +='    <td>: '+item.meninggal+'</td>';
                      data +='  </tr>';
                    }else{
                      console.log(kel.replace(/\s/g,'').toLowerCase());
                      console.log(kec.replace(/\s/g,'').toLowerCase());
                      data = '<table width="300">';
                      data +='  <tr>';
                      data +='    <th colspan="2">Keterangan</th>';
                      data +='  </tr>';
                    
                      data +='  <tr>';
                      data +='    <td>Kabupaten</td>';
                      data +='    <td>: '+kab+'</td>';
                      data +='  </tr>';              
      
                      data +='  <tr style="color:red">';
                      data +='    <td>Kecamatan</td>';
                      data +='    <td>: '+kec+'</td>';
                      data +='  </tr>';

                      data +='  <tr style="color:red">';
                      data +='    <td>Kelurahan</td>';
                      data +='    <td>: '+kel+'</td>';
                      data +='  </tr>';
                    }
                    
                }else{
                  layer.setStyle(defStyle);
                  data = '<table width="300">';
                      data +='  <tr>';
                      data +='    <th colspan="2">Keterangan</th>';
                      data +='  </tr>';
                    
                      data +='  <tr>';
                      data +='    <td>Kabupaten</td>';
                      data +='    <td>: '+kab+'</td>';
                      data +='  </tr>';              
      
                      data +='  <tr>';
                      data +='    <td>Kecamatan</td>';
                      data +='    <td>: '+kec+'</td>';
                      data +='  </tr>';

                      data +='  <tr>';
                      data +='    <td>Kelurahan</td>';
                      data +='    <td>: '+kel+'</td>';
                      data +='  </tr>';  
                }
                layer.bindPopup(data);
                cluster_markers.addLayer( 
                  L.marker(layer.getBounds().getCenter(),{
                    icon: icon_marker
                  }).bindPopup(data)
                );
              });
              map.addLayer(cluster_markers);
              kmz_layer.addTo(map);
          }
      });
      kmzParser.load('bali-kelurahan.kmz');
      var control = L.control.layers(null, null, {
          collapsed: true
      }).addTo(map);
      $('.leaflet-control-layers').hide();

    }
  });
</script>
@endsection