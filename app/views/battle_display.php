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
 <audio id="audio">
  <source src="https://cf-media.sndcdn.com/gJTxrBRbY4n6.128.mp3?Policy=eyJTdGF0ZW1lbnQiOlt7IlJlc291cmNlIjoiKjovL2NmLW1lZGlhLnNuZGNkbi5jb20vZ0pUeHJCUmJZNG42LjEyOC5tcDMqIiwiQ29uZGl0aW9uIjp7IkRhdGVMZXNzVGhhbiI6eyJBV1M6RXBvY2hUaW1lIjoxNzMzNTg0MzUyfX19XX0_&Signature=PTk8dtv7eJ3qoWjyCDboJYDZdYXBVnJBirGBWIQJRegtgELtGaRswh0NXpVvd-L6fsm2XRW7uBAi~JaegmrLACP~9zJZ8bnm6pl9aKOHpIt74eTGgtEB4GjH-k6lKqjilLaAUgGddrUVc1z58EeVz5IlNcLoOEdJZu~r2~HWsBvXYFGUpRJIgfx4eSChFtNA-5vuXa72d8Os7hUdxjr7BJ4kvwHJ0r2OZQCDnbHMdQCsoxaat7Clop5nYoCXkWeZ3QhhPysBInD1l4x3x3UdVTTZkw9PlDCSJT0Ra05~MBn0N0kGmCb7V22Tqr~FNgrsVcNq0X3ysf7m4CBgBjgswg__&Key-Pair-Id=APKAI6TU7MMXM5DG6EPQ" type="audio/mp3">
Your browser does not support the audio element.
</audio> 
<script>
    const battleLogData = <?php echo json_encode($battleLog); ?>;
</script>

<script src="../../public/script.js"></script>

<a href="/battle" class="text-blue-500 hover:underline mt-6 inline-block">New Battle</a>
