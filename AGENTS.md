# AGENTS.md

## 目的
- エージェント共通の作業方針を定義する。

## 基本方針
- 会話言語: 日本語
- コード内コメント: 日本語
- エラーメッセージ: 英語
- PR とコミットは Conventional Commits に従う。
- PR タイトルとコミット本文の言語: PR タイトルは Conventional Commits 形式（英語推奨）。PR 本文は日本語。コミットは Conventional Commits 形式（description は日本語）。
- 日本語と英数字の間には半角スペースを入れる。

## 判断記録のルール
- 判断内容、代替案、採用理由、前提条件、不確実性を明示する。

## 開発手順（概要）
1. プロジェクト理解のために必要なファイルを確認する。
2. 依存関係をインストールする。
3. 変更を実装する。
4. テストと Lint / Format を実行する。

## セキュリティ / 機密情報
- 認証情報やトークンはコミットしない。
- ログに機密情報を出力しない。

## リポジトリ固有
- **type**: Utility/Snippet Repository
- **purpose**: Storage for miscellaneous code and programs
- **published_on**: GitHub Pages (https://book000.github.io/etc/)
- **file_categories**: {'Discord utilities': ['DiscordEmbed.class.php', 'DiscordProgressReporter.class.php', 'DiscordEmbed.java'], 'Web utilities': ['gps.html', 'denchi.html'], 'Minecraft utilities': ['ParseSelector.java', 'Material_To_GiveName.php', 'Materials_1.12.2.txt'], 'Data utilities': ['access_log_parse.php', 'androidbackup.php', 'google_form_auto_answer.php'], 'Railway utilities': ['timetable_to_json.php', 'timetable.json', 'current_train_searcher.php'], 'Game utilities': ['SteamGame.class.php'], 'Other': ['function.php', 'niconico.class.php', 'MCBansLookup.class.php']}
- **note**: Historical code dump - Some files contain legacy code with security issues (plaintext passwords in old commits). Mostly educational/archival value.