package *;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.LinkedHashSet;
import java.util.Set;
import java.util.TimeZone;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;

public class DiscordEmbed {
	private String title = ""; // title of embed(string)
	private String type = "rich"; // type of embed(string)
	private String description = ""; // description of embed(string)
	private String url = "https://jaoafa.com/"; // url of embed(string)
	private String timestamp = ""; // timestamp of embed content(ISO8601)
	private int color = 65280; // color code of the embed(integer)
	private FooterEmbed footer = null; // footer infomation(footer object)
	private ImageEmbed image = null; // image infomation(image object)
	private ThumbnailEmbed thumbnail = null; // thumbnail infomation(thumbnail object)
	private VideoEmbed video = null; // video infomation(video object)
	private ProviderEmbed provider = null; // provider infomation(provider object)
	private AuthorEmbed author = null; // author infomation(author object)
	private Set<FieldEmbed> fields = new LinkedHashSet<>(); // fields infomation(fields object)

	public DiscordEmbed(){
		DateFormat df = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm'Z'");
		df.setTimeZone(TimeZone.getTimeZone("Asia/Tokyo"));
		timestamp = df.format(new Date());
	}

	public String getTitle(){
	    return this.title;
	}

	public void setTitle(String title){
	    this.title = title;
	}

	public String getType(){
	    return this.type;
	}

	public void setType(String type){
	    this.type = type;
	}

	public String getDescription(){
	    return this.description;
	}

	public void setDescription(String description){
	    this.description = description;
	}

	public String getUrl(){
	    return this.url;
	}

	public void setUrl(String url){
	    this.url = url;
	}

	public String getTimestamp(){
	    return this.timestamp;
	}

	public void setTimestamp(String timestamp){
	    this.timestamp = timestamp;
	}

	public int getColor(){
	    return this.color;
	}

	public void setColor(int color){
	    this.color = color;
	}

	public FooterEmbed getFooter(){
		return this.footer;
	}

	@SuppressWarnings("unchecked")
	public JSONObject getFooterJSON(){
		JSONObject json = new JSONObject();
		json.put("text", footer.getText());
		json.put("icon_url", footer.getIcon_url());
		json.put("proxy_icon_url", footer.getProxy_icon_url());
		return json;
	}

	public void setFooter(FooterEmbed footer){
		this.footer = footer;
	}

	public void setFooter(String text, String icon_url, String proxy_icon_url){
		FooterEmbed footer = new FooterEmbed(text, icon_url, proxy_icon_url);
		setFooter(footer);
	}

	public ImageEmbed getImage() {
		return image;
	}

	@SuppressWarnings("unchecked")
	public JSONObject getImageJSON(){
		JSONObject json = new JSONObject();
		json.put("url", image.getUrl());
		json.put("proxy_url", image.getProxy_url());
		json.put("height", image.getHeight());
		json.put("width", image.getWidth());
		return json;
	}

	public void setImage(ImageEmbed image) {
		this.image = image;
	}

	public void setImage(String url, String proxy_url, int height, int width){
		ImageEmbed image = new ImageEmbed(url, proxy_url, height, width);
		setImage(image);
	}

	public ThumbnailEmbed getThumbnail() {
		return thumbnail;
	}

	@SuppressWarnings("unchecked")
	public JSONObject getThumbnailJSON(){
		JSONObject json = new JSONObject();
		json.put("url", thumbnail.getUrl());
		json.put("proxy_url", thumbnail.getProxy_url());
		json.put("height", thumbnail.getHeight());
		json.put("width", thumbnail.getWidth());
		return json;
	}

	public void setThumbnail(ThumbnailEmbed thumbnail) {
		this.thumbnail = thumbnail;
	}

	public void setThumbnail(String url, String proxy_url, int height, int width){
		ThumbnailEmbed thumbnail = new ThumbnailEmbed(url, proxy_url, height, width);
		setThumbnail(thumbnail);
	}

	public VideoEmbed getVideo() {
		return video;
	}

	@SuppressWarnings("unchecked")
	public JSONObject getVideoJSON(){
		JSONObject json = new JSONObject();
		json.put("url", video.getUrl());
		json.put("height", video.getHeight());
		json.put("width", video.getWidth());
		return json;
	}

	public void setVideo(VideoEmbed video) {
		this.video = video;
	}

	public void setVideo(String url, int height, int width) {
		VideoEmbed thumbnail = new VideoEmbed(url, height, width);
		setVideo(thumbnail);
	}

	public ProviderEmbed getProvider() {
		return provider;
	}

	@SuppressWarnings("unchecked")
	public JSONObject getProviderJSON(){
		JSONObject json = new JSONObject();
		json.put("name", provider.getName());
		json.put("url", provider.getUrl());
		return json;
	}

	public void setProvider(ProviderEmbed provider) {
		this.provider = provider;
	}

	public void setProvider(String name, String url) {
		ProviderEmbed provider = new ProviderEmbed(name, url);
		setProvider(provider);
	}

	public AuthorEmbed getAuthor() {
		return author;
	}

	@SuppressWarnings("unchecked")
	public JSONObject getAuthorJSON(){
		JSONObject json = new JSONObject();
		json.put("name", author.getName());
		json.put("url", author.getUrl());
		json.put("icon_url", author.getIcon_url());
		json.put("proxy_icon_url", author.getProxy_icon_url());
		return json;
	}

	public void setAuthor(AuthorEmbed author) {
		this.author = author;
	}

	public void setAuthor(String name, String url, String icon_url, String proxy_icon_url) {
		AuthorEmbed author = new AuthorEmbed(name, url, icon_url, proxy_icon_url);
		setAuthor(author);
	}

	public Set<FieldEmbed> getFields() {
		return fields;
	}

	@SuppressWarnings("unchecked")
	public JSONArray getFieldsJSON(){
		JSONArray json = new JSONArray();
		for(FieldEmbed field : getFields()){
			JSONObject one = new JSONObject();
			one.put("name", field.getName());
			one.put("value", field.getValue());
			one.put("inline", field.getInline());
			json.add(one);
		}
		return json;
	}

	public void setFields(Set<FieldEmbed> fields) {
		this.fields = fields;
	}

	public void addFields(FieldEmbed field) {
		this.fields.add(field);
	}

	public void addFields(String name, String value, boolean inline) {
		FieldEmbed field = new FieldEmbed(name, value, inline);
		this.fields.add(field);
	}

	@SuppressWarnings("unchecked")
	public String build(){
		JSONObject json = new JSONObject();
		json.put("title", getTitle());
		json.put("type", getType());
		json.put("description", getDescription());
		json.put("url", getUrl());
		json.put("timestamp", getTimestamp());
		json.put("color", getColor());

		if(getFooter() != null){
			json.put("footer", getFooterJSON());
		}

		if(getImage() != null){
			json.put("image", getImageJSON());
		}

		if(getThumbnail() != null){
			json.put("thumbnail", getThumbnailJSON());
		}

		if(getVideo() != null){
			json.put("video", getVideoJSON());
		}

		if(getProvider() != null){
			json.put("provider", getProviderJSON());
		}

		if(getAuthor() != null){
			json.put("author", getAuthorJSON());
		}

		if(getFields().size() != 0){
			json.put("fields", getFieldsJSON());
		}

		return json.toJSONString();
	}

	@SuppressWarnings("unchecked")
	public JSONObject buildJSON(){
		JSONObject json = new JSONObject();
		json.put("title", getTitle());
		json.put("type", getType());
		json.put("description", getDescription());
		json.put("url", getUrl());
		json.put("timestamp", getTimestamp());
		json.put("color", getColor());

		if(getFooter() != null){
			json.put("footer", getFooterJSON());
		}

		if(getImage() != null){
			json.put("image", getImageJSON());
		}

		if(getThumbnail() != null){
			json.put("thumbnail", getThumbnailJSON());
		}

		if(getVideo() != null){
			json.put("video", getVideoJSON());
		}

		if(getProvider() != null){
			json.put("provider", getProviderJSON());
		}

		if(getAuthor() != null){
			json.put("author", getAuthorJSON());
		}

		if(getFields().size() != 0){
			json.put("fields", getFieldsJSON());
		}

		return json;
	}
}
