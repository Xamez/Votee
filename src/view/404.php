<?php
echo '
<p id="1">Proposition 1:<br/> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.</p>

<p id="2">Proposition 2:<br/> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.</p>

<div id="popup" class="hidden fixed z-1 top-1/2 left-1/2 bg-main text-white rounded-xl p-4">
    <div class="flex flex-col items-center justify-center gap-2">
        <p class="text-md font-bold border-0 select-none">Ecrivez un commentaire</p>
        <textarea class="border-2 max-h-60 h-44 w-96 bg-main ring-0 focus:outline-none" maxlength="2000" placeholder="Entrez votre commentaire..." type="text" required></textarea>
        <button id="create-commentary" class="text-xl font-bold hover:underline underline-1">Ajouter un commentaire</button>
    </div>
</div>
';
/*echo '<div class="flex flex-col justify-center">';
echo '<img class="justify-center" src="../resources/404.png" alt="404">';
echo '</div>';*/