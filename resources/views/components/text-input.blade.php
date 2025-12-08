@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 focus:shadow-neon-pink transition']) }} style="background: rgba(9, 9, 15, 0.6); border-color: rgba(106, 56, 194, 0.4);" onfocus="this.style.borderColor='#FF3CAC'; this.style.boxShadow='0 0 20px rgba(255, 60, 172, 0.3)';" onblur="this.style.borderColor='rgba(106, 56, 194, 0.4)'; this.style.boxShadow='none';">
