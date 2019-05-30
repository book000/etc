# etc

えとせとら。色んなプログラムやらなんやらを投げ込み、後で何かあったときに拾い上げる用。作ったものを適当に公開しておく場。  
[GitHub Pages](https://book000.github.io/etc/)をオンにしている。なぜだ？

# ファイル解説

一部(？)だけ

## gps.html, denchi.html

2015/10/13初コミット。Syncerあたりのサンプルソースコードをいじったものだと思う。  
gps.htmlでは現在地をマップにプロットし続ける。denchi.htmlでは電池残量をテーブルに追記し続ける。
- [gps.html](https://book000.github.io/etc/gps.html)
- [denchi.html](https://book000.github.io/etc/denchi.html)

## function.php

2016/02/15初コミット。phpのカスタムfunctionを羅列してる？なんかコミット履歴見てたらパスワード平文のままコミットしてて草(既にそのサーバは存在しない)。

## minecraft_some_command.php

2016/03/25初コミット。`$list`に`$`区切りで連続実行したいコマンドを入れ、実行すると赤石ブロックとコマンドブロックを使った連続コマンド実行装置のコマンドを生成する。今見るとすごくコードが汚え。

## DiscordEmbed.class.php

2017/10/14初コミット。DiscordのEmbedを生成するためのPHPクラス。以下のように使う。  
```php
require_once(__DIR__ . "/DiscordEmbed.class.php");
$embed = new DiscordEmbed();
$embed->setTitle("Test");
$embed->setUrl("https://github.com/book000/etc");
DiscordMessageSend("000000000000000000", "", $embed->Export());
```
DiscordMessageSend関数については[ここ](https://tomacheese.com/discordmessagesend/)から。

## ParseSelector.java

2018/03/29初コミット。Minecraftのセレクター(`@p[type=Item]`など)をパースするためのもの。1.12.2で動作？  
実際に使ってるのは[こんな感じ](https://github.com/jaoafa/MyMaid2/blob/97748d55784c54b4ac10125141c93ce8abf7a533/src/main/java/com/jaoafa/MyMaid2/Command/Cmd_Selector.java#L20)。  
決して安定して動作しているとは言えない…。

## DiscordEmbed.java

2018/04/23初コミット。上記DiscordEmbed.class.phpのJava版。こちらはExport()ではなくてbuildJSON()でJSONを生成し吐き出す。  
送信コードは以下。(引用元: [jaoafa/MyMaid2 MyMaid2Premise.java#L190](https://github.com/jaoafa/MyMaid2/blob/e95aaaaac45e669d57f59d9bae3ce95e93ef6f0e/src/main/java/com/jaoafa/MyMaid2/MyMaid2Premise.java#L190))
```java
/**
* Discordへチャンネルを指定してメッセージを送信します。
* @param channel 送信先のチャンネルID
* @param message 送信するメッセージ
* @param embed 送信するEmbed
* @return 送信できたかどうか
*/
@SuppressWarnings("unchecked")
public static boolean DiscordSend(String channel, String message, DiscordEmbed embed){
	if(MyMaid2.discordtoken == null){
		throw new NullPointerException("DiscordSendが呼び出されましたが、discordtokenが登録されていませんでした。");
	}
	Map<String, String> headers = new HashMap<>();
	headers.put("Content-Type", "application/json");
	headers.put("Authorization", "Bot " + MyMaid2.discordtoken);
	headers.put("User-Agent", "DiscordBot (https://jaoafa.com, v0.0.1)");

	JSONObject paramobj = new JSONObject();
	paramobj.put("content", message);
	paramobj.put("embed", embed.buildJSON());
	return postHttpsJsonByJsonObj("https://discordapp.com/api/channels/" + channel + "/messages", headers, paramobj);
}
```

## niconico.class.php

2018/05/10初コミット。ニコニコのログイン処理やログイン後の取得処理なんかを楽にしたくて作ったPHPクラス。詳しく解説はしないし使うことをお勧めはしない。

## SteamGame.class.php

2018/07/14初コミット。Steamのゲームデータを取得するためのPHPクラス。`$game = new SteamGame($appid);`とかで生成し、`$game->getCurrencyPrice()`で現在価格、とか。

## Material_To_GiveName.php, replaceMaterialToGiveName.java, (Materials_1.12.2.txt)

2018/08/14初コミット。Materials_1.12.2.txtにorg.bukkit.Materialのenumをコピペして書き込み、[Minecraft ID ListのAPI](https://minecraft-ids.grahamedgecombe.com/items.json)を使ってMaterial(`Material.name()`)とgive時のアイテム名が違う場合に置き換えるプログラムを生成するためのプログラム。  
[jaoafa/GiveCommandGenerator](https://github.com/jaoafa/GiveCommandGenerator)を作るときに使った。

## androidbackup.php

2018/12/23初コミット。Android端末のAPKファイル、アプリケーションデータ、本体データ、SDカードをバックアップする。1か月に1回程度このプログラムを使ってバックアップをしている。詳しいことはファイル上部のコメントを。

## access_log_parse.php

2019/03/14初コミット。Nginxのアクセスログをパースしてそれぞれ変数に入れるもの。正規表現使って引っこ抜いているだけとも言える。

## google_form_auto_answer.php

2019/03/24初コミット。Googleフォームの自動回答プログラム。

## timetable_to_json.php, timetable.json, current_train_searcher.php

2019/05/15初コミット。電車の時刻表関連。`timetable_to_json.php`はYahoo!の路線情報をパースして`timetable.json`に出力。既に`timetable.json`に「JR中央線快速 高尾・青梅方面」の時刻表を出力済み。  
`current_train_searcher.php`は時刻表から乗れる電車とその前後の電車を出力するプログラム。かなり適当な作りになっているけど気にしない。
