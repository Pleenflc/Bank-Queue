<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bank Homepage</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to right, #363753, #fefefe);
      color: #333;
      margin: 0;
      background-size: 200% 100%;
      animation: moveBackground 5s linear infinite;
    }

    @keyframes moveBackground {
      0% { background-position: 100% 0; }
      25% { background-position: 75% 25%;}
      50% { background-position: 50% 50%;}
      75% { background-position: 25% 75%; }
      100% { background-position: 0 100%; }
    }

     .navbar {
      background-color: #fefefe;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .navbar h1 {
      color: #363753;
      font-size: 42px;
      margin: 0;
      font-weight: bold;
    }

    .navbar p {
      font-size: 14px;
      color: #363753;
      margin-top: 5px;
    }

    .hero {
      background-image: url(https://wallpapers.com/images/high/appealing-illustration-of-investment-c8ftkktcfsh3l6iv.webp);
      background-size: cover;
      background-position: center;
      text-align: center;
      padding: 100px 20px;
      color: #fff;
      position: relative;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 0;
    }

    .hero h2,
    .hero p,
    .hero .cta-button {
      position: relative;
      z-index: 1;
    }

    .hero h2 {
      font-size: 50px;
      font-weight: 700;
      color: #fff7e6;
    }

    .hero h2 span {
      color: #5cd2c6;
    }

    .hero p {
      font-size: 20px;
      max-width: 600px;
      margin: 20px auto;
      color: #eee;
    }

    .cta-button {
      background-color: #5cd2c6;
      color: white;
      border: none;
      padding: 14px 32px;
      font-size: 18px;
      border-radius: 50px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    .cta-button:hover {
      background-color: rgb(50, 120, 113);
      transform: scale(1.05);
    }

    .info-section {
      display: flex;
      justify-content: space-around;
      padding: 50px 20px;
      flex-wrap: wrap;
      gap: 30px;
    }

    .info-box {
  background: linear-gradient(145deg, #fdfdfd, #eaeaea);
  border-radius: 25px;
  padding: 40px 25px;
  width: 280px;
  text-align: center;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease-in-out;
  position: relative;
  overflow: hidden;
}

.info-box:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
  background: linear-gradient(145deg, #f9f9f9, #dfe3ee);
}

.info-box h3 {
  font-size: 20px;
  color: #363753;
  margin-top: 20px;
  font-weight: bold;
}

.info-box p {
  font-size: 14px;
  color: #555;
  margin-top: 10px;
}

.info-box h3::before {
  content: attr(data-icon);
  font-size: 30px;
  display: inline-block;
  background-color: #5cd2c6;
  color: white;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  line-height: 55px;
  margin-bottom: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}


    .info-box p {
      font-size: 15px;
      color: #363753;
    }

    /* Badges */
    .badge-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 30px;
    }

    .badge {
      background-color: #5cd2c6;
      padding: 12px 24px;
      border-radius: 30px;
      font-weight: bold;
      color: #fefefe;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      font-size: 16px;
    }

    .fun-section {
  text-align: center;
  padding: 60px 20px;
  background: linear-gradient(145deg, #e0f7fa, #ffffff);
  margin-top: 50px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.fun-section img {
  width: 140px;
  margin-bottom: 30px;
  filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
  transition: transform 0.3s ease;
}

.fun-section img:hover {
  transform: scale(1.05);
}

.fun-section h4 {
  font-size: 32px;
  color: #363753;
  margin-bottom: 20px;
  font-weight: 700;
}

.fun-section p {
  font-size: 18px;
  color: #555;
  max-width: 600px;
  margin: 0 auto 30px auto;
  line-height: 1.6;
}

.badge-container {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 40px;
}

.badge {
  background-color: #5cd2c6;
  padding: 14px 28px;
  border-radius: 30px;
  font-weight: bold;
  color: #fff;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  font-size: 16px;
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.badge:hover {
  transform: scale(1.05);
  background-color: #48bfb2;
}


    footer {
      text-align: center;
      padding: 20px 0;
      background-color: #fefefe;
      margin-top: 40px;
      font-size: 14px;
      color: #555;
    }
    .info-section a {
  text-decoration: none;
  color: inherit;
}

  </style>
</head>
<body>

<div class="navbar">
  <h1>BANK INDEPENDENT</h1>
  <p>Jl. Bambu Kuning Selatan, Sepanjang Jaya, Rawalumbu, Kota Bks, Jawa Barat</p>
</div>

<div class="hero">
  <h2>Powerfully Simple<br><span>Business Banking</span></h2>
  <p>Built for small business owners, entrepreneurs, and freelancers. No hidden fees, no hassle.</p>
  <button id="redirectButton" class="cta-button">Get Started</button>
</div>

<div class="info-section">
  <a href="teller.php" class="info-box" data-aos="zoom-in">
    <h3 data-icon="🏦"> Teller Services</h3>
    <p>Need to deposit or withdraw? Our friendly tellers are ready to assist you with any transaction.</p>
  </a>
  <a href="cs.php" class="info-box" data-aos="zoom-in">
    <h3 data-icon="💬"> Customer Support</h3>
    <p>We’re here for you — get assistance with your account, services, or financial guidance.</p>
  </a>
  <a href="mobile.php" class="info-box" data-aos="zoom-in">
    <h3 data-icon="📱"> Mobile Banking</h3>
    <p>Access your account anytime and anywhere using our mobile banking app. Easy to use and very secure.</p>
  </a>
  <a href="document.php" class="info-box" data-aos="zoom-in">
    <h3 data-icon="📄"> Document Service</h3>
    <p>We provide document verification and print services. Our officers are ready to help with all your needs.</p>
  </a>
  <a href="assistance.php" class="info-box" data-aos="zoom-in">
    <h3 data-icon="🤝"> Friendly Assistance</h3>
    <p>Our staff are trained to be friendly and informative. We aim to create a pleasant banking experience for every customer.</p>
  </a>
  <a href="account.php" class="info-box" data-aos="zoom-in">
    <h3 data-icon="🔐"> Account Safety</h3>
    <p>We prioritize your security. If you experience any suspicious activity, contact our support immediately.</p>
  </a>
</div>

<div class="fun-section" data-aos="fade-up">
  <img src="https://cdn-icons-png.flaticon.com/512/3135/3135773.png" alt="Support Icon" />
  <h4>Your Trust, Our Commitment</h4>
  <p>At <b>BANK INDEPENDENT</b>, we provide not just services, but meaningful solutions for your life and business.</p>

  <div class="badge-container" data-aos="fade-up">
    <div class="badge">🔒 Secure Banking</div>
    <div class="badge">⚡ Fast Service</div>
    <div class="badge">🌟 Trusted Since 1990</div>
    <div class="badge">📞 24/7 Support</div>
  </div>

  <div style="margin-top: 40px;">
    <h5 style="font-style: italic; color: #363735;">"Banking made simple, service made extraordinary."</h5>
    <p style="color: #555; font-size: 16px; max-width: 700px; margin: 10px auto;">Experience excellence with every transaction. At <b>BANK INDEPENDENT</b>, we are more than a bank — we are your partner in success.</p>

  </div>
</div>

<script>
  document.getElementById('redirectButton').addEventListener('click', function () {
    Swal.fire({
      title: "Let's get you started!",
      icon: 'success',
      confirmButtonColor: '#5cd2c6'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'customer.php';
      }
    });
  });
</script>

<script>
  AOS.init();
</script>

<footer>
  <p>&copy; <?php echo date("Y"); ?> BANK INDEPENDENT. Cikarang.</p>
</footer>

</body>
</html>
