<?php

namespace Cloudinary;

class Cloudinary {
	const CF_SHARED_CDN = "d3jpl91pxevbkh.cloudfront.net";
	const OLD_AKAMAI_SHARED_CDN = "cloudinary-a.akamaihd.net";
	const AKAMAI_SHARED_CDN = "res.cloudinary.com";
	const SHARED_CDN = "res.cloudinary.com";

	private static $config = NULL;
	public static $JS_CONFIG_PARAMS = array("api_key", "cloud_name", "private_cdn", "secure_distribution", "cdn_subdomain");

	public static function config($values = NULL) {
		if (self::$config == NULL) {
			self::reset_config();
		}
		if ($values != NULL) {
			self::$config = array_merge(self::$config, $values);
		}
		return self::$config;
	}

	public static function reset_config() {
		self::config_from_url(getenv("CLOUDINARY_URL"));
	}

	public static function config_from_url($cloudinary_url) {
		self::$config = array();
		if ($cloudinary_url) {
			$uri = parse_url($cloudinary_url);
			$config = array("cloud_name" => $uri["host"],
					"api_key" => $uri["user"],
					"api_secret" => $uri["pass"],
					"secure" => true,
					"private_cdn" => isset($uri["path"]));
			if (isset($uri["path"])) {
				$config["secure_distribution"] = substr($uri["path"], 1);
			}
			self::$config = array_merge(self::$config, $config);
		}
	}

	public static function config_get($option, $default=NULL) {
		return Cloudinary::option_get(self::config(), $option, $default);
	}

	public static function option_get($options, $option, $default=NULL) {
		if (isset($options[$option])) {
			return $options[$option];
		} else {
			return $default;
		}
	}

	public static function option_consume(&$options, $option, $default=NULL) {
		if (isset($options[$option])) {
			$value = $options[$option];
			unset($options[$option]);
			return $value;
		} else {
			unset($options[$option]);
			return $default;
		}
	}

	public static function build_array($value) {
		if (is_array($value) && $value == array_values($value)) {
			return $value;
		} else if ($value == NULL) {
			return array();
		} else {
			return array($value);
		}
	}

	private static function generate_base_transformation($base_transformation) {
		return is_array($base_transformation) ?
		Cloudinary::generate_transformation_string($base_transformation) :
		Cloudinary::generate_transformation_string(array("transformation"=>$base_transformation));
	}

	// Warning: $options are being destructively updated!
	public static function generate_transformation_string(&$options=array()) {
		$generate_base_transformation = "Cloudinary::generate_base_transformation";
		if (is_string($options)) {
			return $options;
		}
		if ($options == array_values($options)) {
			return implode("/", array_map($generate_base_transformation, $options));
		}

		$size = Cloudinary::option_consume($options, "size");
		if ($size) list($options["width"], $options["height"]) = preg_split("/x/", $size);

		$width = Cloudinary::option_get($options, "width");
		$height = Cloudinary::option_get($options, "height");

		$has_layer = Cloudinary::option_get($options, "underlay") || Cloudinary::option_get($options, "overlay");
		$angle = implode(Cloudinary::build_array(Cloudinary::option_consume($options, "angle")), ".");
		$crop = Cloudinary::option_consume($options, "crop");

		$no_html_sizes = $has_layer || !empty($angle) || $crop == "fit" || $crop == "limit";

		if ($width && (floatval($width) < 1 || $no_html_sizes)) unset($options["width"]);
		if ($height && (floatval($height) < 1 || $no_html_sizes)) unset($options["height"]);

		$background = Cloudinary::option_consume($options, "background");
		if ($background) $background = preg_replace("/^#/", 'rgb:', $background);

		$base_transformations = Cloudinary::build_array(Cloudinary::option_consume($options, "transformation"));
		if (count(array_filter($base_transformations, "is_array")) > 0) {
			$base_transformations = array_map($generate_base_transformation, $base_transformations);
			$named_transformation = "";
		} else {
			$named_transformation = implode(".", $base_transformations);
			$base_transformations = array();
		}

		$effect = Cloudinary::option_consume($options, "effect");
		if (is_array($effect)) $effect = implode(":", $effect);

		$border = Cloudinary::option_consume($options, "border");
		if (is_array($border)) {
			$border_width = Cloudinary::option_get($border, "width", "2");
			$border_color = preg_replace("/^#/", 'rgb:', Cloudinary::option_get($border, "color", "black"));
			$border = $border_width . "px_solid_" . $border_color;
		}

		$flags = implode(Cloudinary::build_array(Cloudinary::option_consume($options, "flags")), ".");

		$params = array("w"=>$width, "h"=>$height, "t"=>$named_transformation, "c"=>$crop, "b"=>$background, "e"=>$effect, "bo"=>$border, "a"=>$angle, "fl"=>$flags);
		$simple_params = array("x"=>"x", "y"=>"y", "r"=>"radius", "d"=>"default_image", "g"=>"gravity",
				"q"=>"quality", "p"=>"prefix", "l"=>"overlay", "u"=>"underlay", "f"=>"fetch_format",
				"dn"=>"density", "pg"=>"page", "dl"=>"delay", "cs"=>"color_space", "o"=>"opacity");
		foreach ($simple_params as $param=>$option) {
			$params[$param] = Cloudinary::option_consume($options, $option);
		}

		$params = array_filter($params);
		ksort($params);
		$join_pair = function($key, $value) { return $key . "_" . $value; };
		$transformation = implode(",", array_map($join_pair, array_keys($params), array_values($params)));
		$raw_transformation = Cloudinary::option_consume($options, "raw_transformation");
		$transformation = implode(",", array_filter(array($transformation, $raw_transformation)));
		array_push($base_transformations, $transformation);
		return implode("/", array_filter($base_transformations));
	}

	// Warning: $options are being destructively updated!
	public static function cloudinary_url($source, &$options=array()) {
		$type = Cloudinary::option_consume($options, "type", "upload");

		if ($type == "fetch" && !isset($options["fetch_format"])) {
			$options["fetch_format"] = Cloudinary::option_consume($options, "format");
		}
		$transformation = Cloudinary::generate_transformation_string($options);

		$resource_type = Cloudinary::option_consume($options, "resource_type", "image");
		$version = Cloudinary::option_consume($options, "version");
		$format = Cloudinary::option_consume($options, "format");

		$cloud_name = Cloudinary::option_consume($options, "cloud_name", Cloudinary::config_get("cloud_name"));
		if (!$cloud_name) throw new InvalidArgumentException("Must supply cloud_name in tag or in configuration");
		$secure = Cloudinary::option_consume($options, "secure", Cloudinary::config_get("secure"));
		$private_cdn = Cloudinary::option_consume($options, "private_cdn", Cloudinary::config_get("private_cdn"));
		$secure_distribution = Cloudinary::option_consume($options, "secure_distribution", Cloudinary::config_get("secure_distribution"));
		$cdn_subdomain = Cloudinary::option_consume($options, "cdn_subdomain", Cloudinary::config_get("cdn_subdomain"));
		$cname = Cloudinary::option_consume($options, "cname", Cloudinary::config_get("cname"));
		$shorten = Cloudinary::option_consume($options, "shorten", Cloudinary::config_get("shorten"));

		$original_source = $source;
		if (!$source) return $original_source;

		if (preg_match("/^https?:\//i", $source)) {
			if ($type == "upload" || $type == "asset") return $original_source;
			$source = Cloudinary::smart_escape($source);
		} else {
			$source = Cloudinary::smart_escape(rawurldecode($source));
			if ($format) $source = $source . "." . $format;
		}

		$shared_domain = !$private_cdn;
		if ($secure) {
			if (!$secure_distribution || $secure_distribution == Cloudinary::OLD_AKAMAI_SHARED_CDN) {
				$secure_distribution = $private_cdn ? $cloud_name . "-res.cloudinary.com" : Cloudinary::SHARED_CDN;
			}
			$shared_domain = $shared_domain || $secure_distribution == Cloudinary::SHARED_CDN;
			$prefix = "https://" . $secure_distribution;
		} else {
			$subdomain = $cdn_subdomain ? "a" . ((crc32($source) % 5 + 5) % 5 + 1) . "." : "";
			$host = $cname ? $cname : ($private_cdn ? $cloud_name . "-" : "") . "res.cloudinary.com";
			$prefix = "http://" . $subdomain . $host;
		}
		if ($shared_domain) $prefix .= "/" . $cloud_name;

		if ($shorten && $resource_type == "image" && $type == "upload") {
			$resource_type = "iu";
			$type = "";
		}
		if (strpos($source, "/") && !preg_match("/^https?:\//", $source) && !preg_match("/^v[0-9]+/", $source) && empty($version)) {
			$version = "1";
		}

		return preg_replace("/([^:])\/+/", "$1/", implode("/", array($prefix, $resource_type,
				$type, $transformation, $version ? "v" . $version : "", $source)));
	}

	// Based on http://stackoverflow.com/a/1734255/526985
	private static function smart_escape($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')', '%3A'=>':', '%2F'=>'/');
		return strtr(rawurlencode($str), $revert);
	}

	public static function cloudinary_api_url($action = 'upload', $options = array()) {
		$cloudinary = Cloudinary::option_get($options, "upload_prefix", Cloudinary::config_get("upload_prefix", "https://api.cloudinary.com"));
		$cloud_name = Cloudinary::config_get("cloud_name");
		if (!$cloud_name) throw new InvalidArgumentException("Must supply cloud_name in tag or in configuration");
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		return implode("/", array($cloudinary, "v1_1", $cloud_name, $resource_type, $action));
	}

	public static function random_public_id() {
		return substr(sha1(uniqid(Cloudinary::config_get("api_secret", "") . mt_rand())), 0, 16);
	}

	public static function signed_preloaded_image($result) {
		return $result["resource_type"] . "/upload/v" . $result["version"] . "/" . $result["public_id"] .
		(isset($result["format"]) ? "." . $result["format"] : "") . "#" . $result["signature"];
	}

	public static function zip_download_url($tag, $options=array()) {
		$params = array("timestamp"=>time(), "tag"=>$tag, "transformation" => Cloudinary::generate_transformation_string($options));
		$params = Cloudinary::sign_request($params, $options);
		return Cloudinary::cloudinary_api_url("download_tag.zip", $options) . "?" . http_build_query($params);
	}

	public static function private_download_url($public_id, $format, $options = array()) {
		$cloudinary_params = Cloudinary::sign_request(array(
				"timestamp"=>time(),
				"public_id"=>$public_id,
				"format"=>$format,
				"type"=>Cloudinary::option_get($options, "type"),
				"attachment"=>Cloudinary::option_get($options, "attachment"),
				"expires_at"=>Cloudinary::option_get($options, "expires_at")
		), $options);

		return Cloudinary::cloudinary_api_url("download", $options) . "?" . http_build_query($cloudinary_params);
	}

	public static function sign_request($params, &$options) {
		$api_key = Cloudinary::option_get($options, "api_key", Cloudinary::config_get("api_key"));
		if (!$api_key) throw new \InvalidArgumentException("Must supply api_key");
		$api_secret = Cloudinary::option_get($options, "api_secret", Cloudinary::config_get("api_secret"));
		if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");

		# Remove blank parameters
		$params = array_filter($params);

		$params["signature"] = Cloudinary::api_sign_request($params, $api_secret);
		$params["api_key"] = $api_key;

		return $params;
	}

	public static function api_sign_request($params_to_sign, $api_secret) {
		$params = array();
		foreach ($params_to_sign as $param => $value) {
			if ($value) {
				$params[$param] = is_array($value) ? implode(",", $value) : $value;
			}
		}
		ksort($params);
		$join_pair = function($key, $value) { return $key . "=" . $value; };
		$to_sign = implode("&", array_map($join_pair, array_keys($params), array_values($params)));
		return sha1($to_sign . $api_secret);
	}
	public static function html_attrs($options) {
		ksort($options);
		$join_pair = function($key, $value) { return $key . "='" . $value . "'"; };
		return implode(" ", array_map($join_pair, array_keys($options), array_values($options)));
	}
}



class Error extends \Exception {}
class NotFound extends Error {}
class NotAllowed extends Error {}
class AlreadyExists extends Error {}
class RateLimited extends Error {}
class BadRequest extends Error {}
class GeneralError extends Error {}
class AuthorizationRequired extends Error {}
class Response extends \ArrayObject {
	function __construct($response) {
		parent::__construct(Api::parse_json_response($response));
		$this->rate_limit_reset_at = strtotime($response->headers["X-FeatureRateLimit-Reset"]);
		$this->rate_limit_allowed = intval($response->headers["X-FeatureRateLimit-Limit"]);
		$this->rate_limit_remaining = intval($response->headers["X-FeatureRateLimit-Remaining"]);
	}
}




class Api {
	static $CLOUDINARY_API_ERROR_CLASSES = array(
			400 => "BadRequest",
			401 => "AuthorizationRequired",
			403 => "NotAllowed",
			404 => "NotFound",
			409 => "AlreadyExists",
			420 => "RateLimited",
			500 => "GeneralError"
	);

	function ping($options=array()) {
		return $this->call_api("get", array("ping"), array(), $options);
	}

	function usage($options=array()) {
		return $this->call_api("get", array("usage"), array(), $options);
	}

	function resource_types($options=array()) {
		return $this->call_api("get", array("resources"), array(), $options);
	}

	function resources($options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$type = Cloudinary::option_get($options, "type");
		$uri = array("resources", $resource_type);
		if ($type) array_push($uri, $type);
		return $this->call_api("get", $uri, $this->only($options, array("next_cursor", "max_results", "prefix", "tags")), $options);
	}

	function resources_by_tag($tag, $options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$uri = array("resources", $resource_type, "tags", $tag);
		return $this->call_api("get", $uri, $this->only($options, array("next_cursor", "max_results")), $options);
	}

	function resource($public_id, $options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$type = Cloudinary::option_get($options, "type", "upload");
		$uri = array("resources", $resource_type, $type, $public_id);
		return $this->call_api("get", $uri, $this->only($options, array("exif", "colors", "faces", "image_metadata", "pages", "max_results")), $options);
	}

	function delete_resources($public_ids, $options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$type = Cloudinary::option_get($options, "type", "upload");
		$uri = array("resources", $resource_type, $type);
		return $this->call_api("delete", $uri, array_merge(array("public_ids"=>$public_ids), $this->only($options, array("keep_original"))), $options);
	}

	function delete_resources_by_prefix($prefix, $options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$type = Cloudinary::option_get($options, "type", "upload");
		$uri = array("resources", $resource_type, $type);
		return $this->call_api("delete", $uri, array_merge(array("prefix"=>$prefix), $this->only($options, array("keep_original"))), $options);
	}

	function delete_resources_by_tag($tag, $options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$uri = array("resources", $resource_type, "tags", $tag);
		return $this->call_api("delete", $uri, $this->only($options, array("keep_original")), $options);
	}

	function delete_derived_resources($derived_resource_ids, $options=array()) {
		$uri = array("derived_resources");
		return $this->call_api("delete", $uri, array("derived_resource_ids"=>$derived_resource_ids), $options);
	}

	function tags($options=array()) {
		$resource_type = Cloudinary::option_get($options, "resource_type", "image");
		$uri = array("tags", $resource_type);
		return $this->call_api("get", $uri, $this->only($options, array("next_cursor", "max_results", "prefix")), $options);
	}

	function transformations($options=array()) {
		return $this->call_api("get", array("transformations"), $this->only($options, array("next_cursor", "max_results")), $options);
	}

	function transformation($transformation, $options=array()) {
		$uri = array("transformations", $this->transformation_string($transformation));
		return $this->call_api("get", $uri, $this->only($options, array("max_results")), $options);
	}

	function delete_transformation($transformation, $options=array()) {
		$uri = array("transformations", $this->transformation_string($transformation));
		return $this->call_api("delete", $uri, array(), $options);
	}

	# updates - currently only supported update is the "allowed_for_strict" boolean flag
	function update_transformation($transformation, $updates=array(), $options=array()) {
	$uri = array("transformations", $this->transformation_string($transformation));
	$params = $this->only($updates, array("allowed_for_strict"));
			if (isset($updates["unsafe_update"])) {
					$params["unsafe_update"] = $this->transformation_string($updates["unsafe_update"]);
    }
    return $this->call_api("put", $uri, $params, $options);
	}

	function create_transformation($name, $definition, $options=array()) {
	$uri = array("transformations", $name);
			return $this->call_api("post", $uri, array("transformation"=>$this->transformation_string($definition)), $options);
	}

	protected function call_api($method, $uri, $params, &$options) {
	$prefix = Cloudinary::option_get($options, "upload_prefix", Cloudinary::config_get("upload_prefix", "https://api.cloudinary.com"));
    $cloud_name = Cloudinary::option_get($options, "cloud_name", Cloudinary::config_get("cloud_name"));
    if (!$cloud_name) throw new \InvalidArgumentException("Must supply cloud_name");
    $api_key = Cloudinary::option_get($options, "api_key", Cloudinary::config_get("api_key"));
    if (!$api_key) throw new \InvalidArgumentException("Must supply api_key");
    $api_secret = Cloudinary::option_get($options, "api_secret", Cloudinary::config_get("api_secret"));
    if (!$api_secret) throw new \InvalidArgumentException("Must supply api_secret");
    $api_url = implode("/", array_merge(array($prefix, "v1_1", $cloud_name), $uri));
    $api_url .= "?" . preg_replace("/%5B\d+%5D/", "%5B%5D", http_build_query($params));
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "{$api_key}:{$api_secret}");
    curl_setopt($ch, CURLOPT_CAINFO,realpath(dirname(__FILE__))."/cacert.pem");
    $response = $this->execute($ch);
    $curl_error = NULL;
    if(curl_errno($ch))
    {
    $curl_error = curl_error($ch);
    }
    curl_close($ch);
    if ($curl_error != NULL) {
    throw new GeneralError("Error in sending request to server - " . $curl_error);
    }
    if ($response->responseCode == 200) {
    return new Response($response);
    } else {
    $exception_class = Cloudinary::option_get(self::$CLOUDINARY_API_ERROR_CLASSES, $response->responseCode);
    if (!$exception_class) throw new GeneralError("Server returned unexpected status code - {$response->responseCode} - {$response->body}");
    $json = $this->parse_json_response($response);
    throw new $exception_class($json["error"]["message"]);
    }
    }

    # Based on http://snipplr.com/view/17242/
    protected function execute($ch) {
    $string = curl_exec($ch);
    $headers = array();
    $content = '';
    $str = strtok($string, "\n");
    $h = null;
    while ($str !== false) {
    if ($h and trim($str) === '') {
    	$h = false;
    	continue;
    	}
    		if ($h !== false and false !== strpos($str, ':')) {
    		$h = true;
    		list($headername, $headervalue) = explode(':', trim($str), 2);
    		$headervalue = ltrim($headervalue);
    		if (isset($headers[$headername]))
    			$headers[$headername] .= ',' . $headervalue;
    			else
    			$headers[$headername] = $headervalue;
    		}
    		if ($h === false) {
    		$content .= $str."\n";
    		}
    		$str = strtok("\n");
    }
    $result = new \stdClass;
    	$result->headers = $headers;
    	$result->body = trim($content);
    	$result->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	return $result;
    }

    static function parse_json_response($response) {
    $result = json_decode($response->body, TRUE);
    if ($result == NULL) {
    $error = json_last_error();
    throw new GeneralError("Error parsing server response ({$response->responseCode}) - {$response->body}. Got - {$error}");
    }
    return $result;
    }

    protected function only(&$hash, $keys) {
    $result = array();
    foreach ($keys as $key) {
    if (isset($hash[$key])) $result[$key] = $hash[$key];
    }

    return $result;
    }

    protected function transformation_string($transformation) {
    return is_string($transformation) ? $transformation : Cloudinary::generate_transformation_string($transformation);
    }
    }


    class Uploader {
    public static function build_upload_params(&$options)
    {
    $params = array("timestamp" => time(),
    "transformation" => Cloudinary::generate_transformation_string($options),
    "public_id" => Cloudinary::option_get($options, "public_id"),
    "callback" => Cloudinary::option_get($options, "callback"),
				"format" => Cloudinary::option_get($options, "format"),
    		"backup" => Cloudinary::option_get($options, "backup"),
    		"faces" => Cloudinary::option_get($options, "faces"),
    		"image_metadata" => Cloudinary::option_get($options, "image_metadata"),
				"exif" => Cloudinary::option_get($options, "exif"),
				"colors" => Cloudinary::option_get($options, "colors"),
				"type" => Cloudinary::option_get($options, "type"),
						"eager" => Uploader::build_eager(Cloudinary::option_get($options, "eager")),
						"headers" => Uploader::build_custom_headers(Cloudinary::option_get($options, "headers")),
    				"use_filename" => Cloudinary::option_get($options, "use_filename"),
				"discard_original_filename" => Cloudinary::option_get($options, "discard_original_filename"),
    						"notification_url" => Cloudinary::option_get($options, "notification_url"),
    				"eager_notification_url" => Cloudinary::option_get($options, "eager_notification_url"),
    		"eager_async" => Cloudinary::option_get($options, "eager_async"),
    		"invalidate" => Cloudinary::option_get($options, "invalidate"),
    		"proxy" => Cloudinary::option_get($options, "proxy"),
    		"folder" => Cloudinary::option_get($options, "folder"),
    		"tags" => implode(",", Cloudinary::build_array(Cloudinary::option_get($options, "tags"))));
    		return array_filter($params);
    }

    public static function upload($file, $options = array())
    {
    $params = Uploader::build_upload_params($options);
    return Uploader::call_api("upload", $params, $options, $file);
    }

    public static function destroy($public_id, $options = array())
    {
    $params = array(
    		"timestamp" => time(),
    		"type" => Cloudinary::option_get($options, "type"),
    		"invalidate" => Cloudinary::option_get($options, "invalidate"),
    				"public_id" => $public_id
    		);
    		return Uploader::call_api("destroy", $params, $options);
    }

    public static function rename($from_public_id, $to_public_id, $options = array())
    {
    $params = array(
    		"timestamp" => time(),
    		"type" => Cloudinary::option_get($options, "type"),
				"from_public_id" => $from_public_id,
				"to_public_id" => $to_public_id,
						"overwrite" => Cloudinary::option_get($options, "overwrite")
						);
						return Uploader::call_api("rename", $params, $options);
    }

    public static function explicit($public_id, $options = array())
	{
		$params = array(
				"timestamp" => time(),
				"public_id" => $public_id,
				"type" => Cloudinary::option_get($options, "type"),
				"callback" => Cloudinary::option_get($options, "callback"),
				"eager" => Uploader::build_eager(Cloudinary::option_get($options, "eager")),
				"headers" => Uploader::build_custom_headers(Cloudinary::option_get($options, "headers")),
				"tags" => implode(",", Cloudinary::build_array(Cloudinary::option_get($options, "tags")))
				);
		return Uploader::call_api("explicit", $params, $options);
    }

    public static function generate_sprite($tag, $options = array())
    {
    $transformation = Cloudinary::generate_transformation_string(
    		array_merge(array("fetch_format"=>Cloudinary::option_get($options, "format")), $options));
    		$params = array(
    				"timestamp" => time(),
    				"tag" => $tag,
    				"async" => Cloudinary::option_get($options, "async"),
				"notification_url" => Cloudinary::option_get($options, "notification_url"),
				"transformation" => $transformation
    );
    return Uploader::call_api("sprite", $params, $options);
    }

    public static function multi($tag, $options = array())
    {
    $transformation = Cloudinary::generate_transformation_string($options);
		$params = array(
				"timestamp" => time(),
				"tag" => $tag,
						"format" => Cloudinary::option_get($options, "format"),
				"async" => Cloudinary::option_get($options, "async"),
						"notification_url" => Cloudinary::option_get($options, "notification_url"),
								"transformation" => $transformation
    );
    return Uploader::call_api("multi", $params, $options);
    }

    public static function explode($public_id, $options = array())
    {
    $transformation = Cloudinary::generate_transformation_string($options);
    		$params = array(
    				"timestamp" => time(),
    				"public_id" => $public_id,
    				"format" => Cloudinary::option_get($options, "format"),
    				"type" => Cloudinary::option_get($options, "type"),
    		"notification_url" => Cloudinary::option_get($options, "notification_url"),
    "transformation" => $transformation
    );
    return Uploader::call_api("explode", $params, $options);
    }

    // options may include 'exclusive' (boolean) which causes clearing this tag from all other resources
    public static function add_tag($tag, $public_ids = array(), $options = array())
    {
    $exclusive = Cloudinary::option_get($options, "exclusive");
    $command = $exclusive ? "set_exclusive" : "add";
    return Uploader::call_tags_api($tag, $command, $public_ids, $options);
	}

    		public static function remove_tag($tag, $public_ids = array(), $options = array())
    		{
    		return Uploader::call_tags_api($tag, "remove", $public_ids, $options);
    }

    public static function replace_tag($tag, $public_ids = array(), $options = array())
    {
    return Uploader::call_tags_api($tag, "replace", $public_ids, $options);
    }

    		public static function call_tags_api($tag, $command, $public_ids = array(), &$options = array())
    		{
		$params = array(
    		"timestamp" => time(),
    		"tag" => $tag,
    				"public_ids" => Cloudinary::build_array($public_ids),
    				"type" => Cloudinary::option_get($options, "type"),
				"command" => $command
    );
		return Uploader::call_api("tags", $params, $options);
    }

    private static $TEXT_PARAMS = array("public_id", "font_family", "font_size", "font_color", "text_align", "font_weight", "font_style", "background", "opacity", "text_decoration");

    public static function text($text, $options = array())
    {
		$params = array("timestamp" => time(), "text" => $text);
		foreach (Uploader::$TEXT_PARAMS as $key) {
		$params[$key] = Cloudinary::option_get($options, $key);
    }
    return Uploader::call_api("text", $params, $options);
}

    public static function call_api($action, $params, $options = array(), $file = NULL)
    {
    $return_error = Cloudinary::option_get($options, "return_error");

    $params = Cloudinary::sign_request($params, $options);

    $api_url = Cloudinary::cloudinary_api_url($action, $options);

		# Serialize params
		$api_url .= "?" . preg_replace("/%5B\d+%5D/", "%5B%5D", http_build_query($params));

    $ch = curl_init($api_url);

    		$post_params = array();
    		if ($file) {
    		if (!preg_match('/^@|^https?:|^s3:|^data:[^;]*;base64,([a-zA-Z0-9\/+\n=]+)$/', $file)) {
    		$post_params["file"] = "@" . $file;
} else {
$post_params["file"] = $file;
			}
}

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
curl_setopt($ch, CURLOPT_CAINFO,realpath(dirname(__FILE__))."/cacert.pem");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$curl_error = NULL;
	if(curl_errno($ch))
		{
			$curl_error = curl_error($ch);
}

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$response_data = $response;

		curl_close($ch);
		if ($curl_error != NULL) {
		throw new \Exception("Error in sending request to server - " . $curl_error);
		}
		if ($code != 200 && $code != 400 && $code != 500 && $code != 401 && $code != 404) {
		throw new \Exception("Server returned unexpected status code - " . $code . " - " . $response_data);
}
		$result = json_decode($response_data, TRUE);
		if ($result == NULL) {
		throw new \Exception("Error parsing server response (" . $code . ") - " . $response_data);
}
if (isset($result["error"])) {
if ($return_error) {
$result["error"]["http_code"] = $code;
} else {
throw new \Exception($result["error"]["message"]);
}
}
return $result;
}
protected static function build_eager($transformations) {
$eager = array();
    foreach (Cloudinary::build_array($transformations) as $trans) {
    $transformation = $trans;
    $format = Cloudinary::option_consume($transformation, "format");
    $single_eager = implode("/", array_filter(array(Cloudinary::generate_transformation_string($transformation), $format)));
    array_push($eager, $single_eager);
    }
    	return implode("|", $eager);
    }

    protected static function build_custom_headers($headers) {
    if ($headers == NULL) {
    return NULL;
    } elseif (is_string($headers)) {
    return $headers;
    } elseif ($headers == array_values($headers)) {
    return implode("\n", $headers);
} else {
$join_pair = function($key, $value) { return $key . ": " . $value; };
			return implode("\n", array_map($join_pair, array_keys($headers), array_values($headers)));
}
}
}

class PreloadedFile {
public static $PRELOADED_CLOUDINARY_PATH = "/^([^\/]+)\/([^\/]+)\/v(\d+)\/([^#]+)#([^\/]+)$/";

public $filename, $version, $public_id, $signature, $resource_type, $type;

public function __construct($file_info) {
if (preg_match(PreloadedFile::$PRELOADED_CLOUDINARY_PATH, $file_info, $matches)) {
$this->resource_type = $matches[1];
$this->type = $matches[2];
$this->version = $matches[3];
$this->filename = $matches[4];
$this->signature = $matches[5];

$public_id_and_format = $this->split_format($this->filename);
			$this->public_id = $public_id_and_format[0];
			$this->format = $public_id_and_format[1];

} else {
throw new \InvalidArgumentException("Invalid preloaded file info");
}
}

public function is_valid() {
		$public_id = $this->resource_type == "raw" ? $this->filename : $this->public_id;
		$expected_signature = Cloudinary::api_sign_request(array("public_id" => $public_id, "version" => $this->version), Cloudinary::config_get("api_secret"));
		return $this->signature == $expected_signature;
		}

		protected function split_format($identifier) {
		$last_dot = strrpos($identifier, ".");

		if ($last_dot === false) {
		return array($identifier, NULL);
}
$public_id = substr($identifier, 0, $last_dot);
$format = substr($identifier, $last_dot+1);
return array($public_id, $format);
		}

		public function identifier() {
		return "v" . $this->version . "/" . $this->filename;
		}


		public function __toString() {
		return $this->resource_type . "/" . $this->type . "/v" . $this->version . "/" . $this->filename . "#" . $this->signature;
		}

		}


?>


