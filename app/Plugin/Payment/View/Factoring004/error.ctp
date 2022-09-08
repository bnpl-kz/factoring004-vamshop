<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    />
    <meta name="theme-color" content="#000000" />
      <style>
          *,
          *::before,
          *::after {
              box-sizing: border-box;
          }

          body,
          h1,
          p {
              font-family: system-ui !important;
              margin: 0;
          }

          .container {
              padding: 14px 16px;
              width: 100%;
              margin: 0 auto;
              max-width: 519px;
              box-sizing: border-box;
          }

          .main {
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: space-between;
              height: calc(100vh - 40px);
          }

          .content {
              padding-top: 136px;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
          }

          .icon_wrapper {
              margin-bottom: 72px;
          }

          .icon_wrapper svg {
              width: 126px;
              height: 126px;
          }

          .title {
              font-weight: 700;
              font-size: 18px;
              line-height: 22px;
              text-align: center;
              color: #172a3f;
              margin-bottom: 26px;
          }

          .description {
              font-size: 16px;
              line-height: 20px;
              text-align: center;
              letter-spacing: 0.5px;
              color: #6d7986;
          }

          .go-back-button {
              width: 100%;
              height: 48px;
              display: flex;
              justify-content: center;
              align-items: center;
              background: #00a755;
              border-radius: 4px;
              font-weight: 600;
              font-size: 17px;
              line-height: 20px;
              color: #ffffff;
              text-decoration: none;
          }

          @media (min-width: 678px) {
              .main {
                  justify-content: flex-start;
              }
              .icon_wrapper {
                  margin-bottom: 90px;
              }
              .icon_wrapper svg {
                  width: 320px;
                  height: 320px;
              }
              .title {
                  font-size: 30px;
                  line-height: 36px;
              }
              .description {
                  font-size: 18px;
                  line-height: 24px;
                  margin-bottom: 60px;
              }
              .go-back-button {
                  width: 210px;
                  height: 50px;
                  background: #f1463b;
              }
          }

      </style>
  </head>
  <body>
    <div class="container">
      <main class="main">
        <div class="content">
          <div class="icon_wrapper">
            <svg
              width="151"
              height="150"
              viewBox="0 0 151 150"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M136.005 27.2011L121.631 41.575C118.146 45.0596 112.702 45.0596 109.217 41.575C105.732 38.0905 105.732 32.6458 109.217 29.1612L123.591 14.7873C113.355 9.77823 100.723 11.5205 92.2296 20.0142C83.7359 28.5079 81.9936 41.1395 87.0027 51.3754L32.5561 105.822C27.3293 104.733 21.449 106.04 17.3111 110.178C10.9953 116.494 10.9953 126.947 17.3111 133.263C23.6269 139.579 34.0806 139.579 40.3964 133.263C44.5344 129.125 46.0589 123.463 44.7522 118.018L99.1988 63.5715C109.435 68.5806 122.066 66.8383 130.56 58.3446C139.054 49.8509 141.014 37.4371 136.005 27.2011ZM34.734 127.165C31.685 130.214 26.8937 130.214 24.0625 127.165C21.0134 124.116 21.0134 119.325 24.0625 116.494C27.1115 113.662 31.9028 113.445 34.734 116.494C37.5652 119.543 37.783 124.116 34.734 127.165Z"
                fill="#6AC5F8"
              />
              <path
                d="M130.56 121.721L54.5523 45.931L56.2946 44.1887L51.7211 30.6859L22.7555 16.0942L12.5195 26.3302L27.9824 56.1669L40.614 59.8693L42.3563 58.127L118.146 134.135L130.56 121.721Z"
                fill="#B3B3B3"
              />
              <path
                d="M130.125 133.699C125.116 138.708 116.622 138.708 111.613 133.699L83.9541 105.822C78.945 100.813 78.945 92.3194 83.9541 87.3103C88.9632 82.3012 97.4568 82.3012 102.466 87.3103L130.125 115.187C135.134 120.196 135.134 128.472 130.125 133.699Z"
                fill="#F94060"
              />
            </svg>
          </div>
          <h1 class="title">Технические доработки</h1>
          <p class="description">
            Улучшаем сервис для Вас. Попробуйте оформить покупку позднее.
          </p>
        </div>

        <a href="/" class="go-back-button">Вернуться в магазин</a>
      </main>
    </div>
  </body>
</html>