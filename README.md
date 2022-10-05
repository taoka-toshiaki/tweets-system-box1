# Twitter ツイート管理システム 概要
- まだ試作段階です。
- 商用利用可能です（連絡不要）。
- 主に自身が使用する時に必要としている機能だけを取り入れています。
  - 機能
    - 即時ツイート機能
    - 予約ツイート機能
    - 予約ツイート編集機能
    - 予約ツイート削除機能
# 動作環境 php7.1 ~ php8.0  
# 使用方法  
- data/tw-config-v2.phpを設定後、index.phpをブラウザで開き使用する。
  - cronの設定
    - tw-post.phpにパラメーター付き（user）で処理をキックするように設定する(一分間がベスト)。
# 前提条件  
- TwitterOAuthのライブラリがvendorディレクトリの中に入っていること(githubにディレクトリ存在しません)。
- Twittwer API V2の申請 各種API Key取得済

# 画面 
![SnapCrab_NoName_2022-10-5_16-10-21_No-00](https://user-images.githubusercontent.com/71550806/194001708-1ec61232-f379-4f60-83e7-6be1b3ecc7e5.png)
![SnapCrab_NoName_2022-10-5_16-10-54_No-00](https://user-images.githubusercontent.com/71550806/194001771-6cdc08b2-f9be-4a0b-b936-49dc56ae51e8.png)
