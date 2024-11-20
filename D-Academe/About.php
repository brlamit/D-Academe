<?php
function renderAbout() {
    return '
    <div class="flex flex-col items-center py-12 px-6 bg-gradient-to-r from-gray-700 via-gray-800 to-gray-900 rounded-lg shadow-lg mt-24 max-w-4xl mx-auto ">
      <h2 class="text-4xl font-extrabold text-white mb-8 text-center">About Us</h2>
      <p class="text-gray-200 mb-6 text-lg leading-relaxed text-center max-w-3xl">
        At <span class="font-bold text-white">D-Academe</span>, we believe that learning should be accessible, equitable, and free from traditional barriers. Our decentralized platform empowers individuals to take control of their education by connecting learners, educators, and content creators from around the globe in a collaborative, peer-to-peer environment.
      </p>
      <p class="text-gray-200 mb-6 text-lg leading-relaxed text-center max-w-3xl">
        We aim to revolutionize education by leveraging the power of decentralization. By removing intermediaries and central authorities, we provide a transparent, learner-centric experience that prioritizes your needs, not profits.
      </p>
      <p class="text-gray-200 mb-6 text-lg leading-relaxed text-center max-w-3xl">
        Our platform uses blockchain technology to ensure that educational content is secure, accessible, and verifiable. Courses, resources, and credentials are distributed across a decentralized network, giving you complete control over your learning journey. Earn and share credentials directly without the need for traditional institutions.
      </p>
      <p class="text-gray-200 text-lg leading-relaxed text-center max-w-3xl">
        Whether you’re a learner, an educator, or a creator, <span class="font-bold text-white">D-Academe</span> offers a place to connect, collaborate, and grow. Join us in building a future where education is truly open and accessible to all.
      </p>
    </div>';
}

echo renderAbout();
?>
