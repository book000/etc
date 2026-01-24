# GEMINI.md

## 目的
- Gemini CLI 向けのコンテキストと作業方針を定義する。

## 出力スタイル
- 言語: 日本語
- トーン: 簡潔で事実ベース
- 形式: Markdown

## 共通ルール
- 会話は日本語で行う。
- PR とコミットは Conventional Commits に従う。
- PR タイトルとコミット本文の言語: PR タイトルは Conventional Commits 形式（英語推奨）。PR 本文は日本語。コミットは Conventional Commits 形式（description は日本語）。
- 日本語と英数字の間には半角スペースを入れる。

## プロジェクト概要
Collection of miscellaneous code snippets and utilities. Dumping ground for various programs and scripts in multiple languages.

### 技術スタック
- **言語**: PHP, Java, HTML, JavaScript
- **フレームワーク**: V, a, r, i, o, u, s, /, N, o, n, e

## コーディング規約
- フォーマット: 既存設定（ESLint / Prettier / formatter）に従う。
- 命名規則: 既存のコード規約に従う。
- コメント言語: 日本語
- エラーメッセージ: 英語

### 開発コマンド
```bash
```

## 注意事項
- 認証情報やトークンはコミットしない。
- ログに機密情報を出力しない。
- 既存のプロジェクトルールがある場合はそれを優先する。

## リポジトリ固有
- **type**: Utility/Snippet Repository
- **purpose**: Storage for miscellaneous code and programs
- **published_on**: GitHub Pages (https://book000.github.io/etc/)
- **file_categories**: {'Discord utilities': ['DiscordEmbed.class.php', 'DiscordProgressReporter.class.php', 'DiscordEmbed.java'], 'Web utilities': ['gps.html', 'denchi.html'], 'Minecraft utilities': ['ParseSelector.java', 'Material_To_GiveName.php', 'Materials_1.12.2.txt'], 'Data utilities': ['access_log_parse.php', 'androidbackup.php', 'google_form_auto_answer.php'], 'Railway utilities': ['timetable_to_json.php', 'timetable.json', 'current_train_searcher.php'], 'Game utilities': ['SteamGame.class.php'], 'Other': ['function.php', 'niconico.class.php', 'MCBansLookup.class.php']}
- **note**: Historical code dump - Some files contain legacy code with security issues (plaintext passwords in old commits). Mostly educational/archival value.