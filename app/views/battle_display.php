<div class="flex justify-center" id="audiotext">
    <span class="text-center text-black">Click anywhere to play audio</span>
</div>
<div class="relative">
<canvas id="battleCanvas" class="w-[850px] h-[650px] bg-contain bg-no-repeat rounded-lg" width="850" height="650" style="background-image: url('https://preview.redd.it/d9spuwer2c491.png?width=1050&format=png&auto=webp&s=9ca8c75c63da9f8bb134e955d73e2770d073375e')"></canvas>
<div id="battleLogOverlay" class="absolute bottom-4 left-4 h-[170px] w-[810px] bg-black/80 text-white p-4 rounded-lg">
    <!-- Battle log will be displayed here -->
</div>
</div>

<div id="battleLogContainer" class="mt-4 p-4 w-100 bg-gray-100 rounded-lg max-h-[300px] overflow-y-auto">
    <!-- Permanent battle log will be displayed here -->
</div>
 <audio id="audio" loop>
  <source src="https://vgmsite.com/soundtracks/pok-mon-diamond-pok-mon-pearl-super-music-collection-2006/wgtkjtqysl/26.%20Battle%21%20%28Trainer%29.mp3" type="audio/mp3">
Your browser does not support the audio element.
</audio> 
<script>
    const battleLogData = <?php echo json_encode($battleLog); ?>;
</script>

<script src="../../public/script.js"></script>

<a href="/battle" class="text-blue-500 hover:underline mt-6 inline-block">New Battle</a>
