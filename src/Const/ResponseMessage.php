<?php
/**
 * @author: sma01
 * @since: 2024/6/19
 * @version: 1.0
 */


namespace App\Const;

class ResponseMessage
{
    const UNKNOWN_ERROR = 9999;
    const HTTP_ERROR = 4000;
    const REQUEST_MAPPING_DTO_INVALID = 4001;
    const PRODUCT_SEND_SEARCH_DATE_INVALID = 4002;
    const INVALID_MARKET = 4003;
    const DTO_INITIALIZE_ERROR = 4004;
    const NO_IMAGES_UPLOADED = 4101;
    const IMAGE_CONSTRAINT_ERROR = 4102;
    const SECURITY_NOT_USER = 4031;
    const SECURITY_PASSWORD_ERROR = 4032;
    const SECURITY_JWT_AUTH_FAIL = 4033;
    const SECURITY_JWT_CREATE_FAIL = 4034;
    const SECURITY_JWT_INVALID = 4035;
    const SECURITY_JWT_EXPIRE = 4036;
    const SECURITY_NOT_PERMISSION = 4037;
    const SECURITY_USER_NOT_ENABLE = 4038;
    const SYSTEM_ERROR = 5000;
    const IMAGE_UPLOAD_FAILURE = 5001;
    const FTP_CONNECTION_FAILURE = 5002;
    const FTP_LOGIN_FAILURE = 5003;
    const FTP_PUT_FAILURE = 5004;
    const SQL_ERROR = 5555;
    const SQL_UNIQUE_ERROR = 5556;
    const SYSTEM_SERIALIZE_ERROR = 5444; // 序列化
    const SYSTEM_DAO_SERIALIZE_ERROR = 5445;
    const ACCOUNT_ERROR = 5100;
    const PRODUCT_ERROR = 5200;
    const SHOP_MARKET_TOKEN_EMPTY = 5101;
    const SHOP_MARKET_ACCESS_KEY_DUPLICATE = 5102;
    const SHOP_MARKET_SECRET_KEY_DUPLICATE = 5103;
    const SHOP_ID_DUPLICATE = 5104;
    const USER_SELLER_REF_CODE_INVALID = 5105;
    const USER_RECHARGE_NOT_ENOUGH = 5106;
    const USERNAME_DUPLICATE = 5107;
    const USER_CREATE_PARENT_NOT_FOUND = 5108;
    const USER_CRATE_PARENT_ACC_TYPE_ERROR = 5109;
    const USER_ROLE_CODE_DUPLICATE = 5110;
    const SHOP_SK11ST_CHECK_OUT_ADDRESS_ERROR = 5111;
    const SHOP_SK11ST_CHECK_OUT_COST_ERROR = 5112;
    const SHOP_SK11ST_IS_NOT_OVERSEAS = 5113;
    const PRODUCT_UNTRANSLATED = 5201;
    const PRODUCT_SEND_DUPLICATE = 5202;
    const PRODUCT_SKU_REQUIRED = 5203;
    const PRODUCT_NOT_FIND = 5204;
    const PRODUCT_OPTION_REQUIRED = 5205;
    const PRODUCT_OPTION_INVALID = 5206;
    const PRODUCT_OPTION_PVS_INVALID = 5207;
    const PRODUCT_STOCK_INVALID = 5208;
    const PRODUCT_SKU_PRICE_INVALID = 5209;
    const PRODUCT_FAVORITE_NOT_ALLOW = 5210;
    const PRODUCT_FAVORITE_DUPLICATE = 5211;
    const PRODUCT_FAVORITE_USER_ERROR = 5212;
    const PRODUCT_OPTION_NO_IMAGE = 5213;
    const PRODUCT_FAVORITE_SAVE_ERROR = 5214;
    const PRODUCT_MODIFY_LIMIT_SKU = 5215;
    const PRODUCT_MODIFY_LIMIT_OPTION_NAME = 5216;
    const PRODUCT_MODIFY_LIMIT_MARKET_CATEGORY = 5217;
    const PRODUCT_SEND_PROCESSING = 5218;
    const ORDER_ERROR = 5300;
    const ORDER_TAOBAO_WORLD_PRODUCT_NOT_FOUND = 5399;
    const QNA_ERROR = 5400;
    const QNA_COUPANG_REQUEST_ANSWER = 5401;
    const MARKET_CLIENT_NOT_COMMAND = 6101;
    const MARKET_CLIENT_REQUEST_ERROR = 6102;
    const MARKET_CLIENT_CONTENT_ERROR = 6103;

    public static array $codeMessage = [
        self::UNKNOWN_ERROR                        => 'Unknown exception, contact the administrator to handle it promptly.',
        self::SECURITY_JWT_AUTH_FAIL               => 'API key authentication failed.',
        self::SECURITY_JWT_CREATE_FAIL             => 'API key generation failed.',
        self::SECURITY_JWT_INVALID                 => 'API key parameter is invalid.',
        self::SECURITY_JWT_EXPIRE                  => 'Expires API key.',
        self::REQUEST_MAPPING_DTO_INVALID          => 'Request parameter error is invalid.',
        self::NO_IMAGES_UPLOADED                   => 'No pictures uploaded.',
        self::FTP_CONNECTION_FAILURE               => 'ftp connection failure.',
        self::FTP_LOGIN_FAILURE                    => 'ftp login failure.',
        self::FTP_PUT_FAILURE                      => 'ftp put content failure.',
        self::IMAGE_CONSTRAINT_ERROR               => 'Image verification failed.',
        self::IMAGE_UPLOAD_FAILURE                 => 'Image upload failed.',
        self::SECURITY_NOT_USER                    => 'No valid user.',
        self::SECURITY_PASSWORD_ERROR              => 'Incorrect password.',
        self::SYSTEM_SERIALIZE_ERROR               => 'Data serialization failed.',
        self::PRODUCT_SEND_SEARCH_DATE_INVALID     => 'Date parameter is required.',
        self::PRODUCT_UNTRANSLATED                 => 'Product not translated.',
        self::PRODUCT_SEND_DUPLICATE               => 'Product has send.',
        self::PRODUCT_SKU_REQUIRED                 => 'Product sku is required.',
        self::PRODUCT_NOT_FIND                     => 'Product not find',
        self::PRODUCT_OPTION_REQUIRED              => 'The options is required.',
        self::PRODUCT_OPTION_INVALID               => 'The options is invalid.',
        self::PRODUCT_OPTION_PVS_INVALID           => 'The pvs is invalid.',
        self::PRODUCT_STOCK_INVALID                => 'The sku stock is invalid.',
        self::PRODUCT_SKU_PRICE_INVALID            => 'The sku price is invalid.',
        self::INVALID_MARKET                       => 'THE market is invalid.',
        self::SECURITY_NOT_PERMISSION              => 'User not admin.',
        self::SQL_UNIQUE_ERROR                     => 'sql unique error.',
        self::DTO_INITIALIZE_ERROR                 => 'dto initialize error.',
        self::MARKET_CLIENT_NOT_COMMAND            => 'market client not command.',
        self::MARKET_CLIENT_REQUEST_ERROR          => 'market client request error',
        self::MARKET_CLIENT_CONTENT_ERROR          => 'market client content error',
        self::SYSTEM_DAO_SERIALIZE_ERROR           => 'dao to entity error.',
        self::SHOP_MARKET_TOKEN_EMPTY              => 'market token should not be blank.',
        self::SHOP_MARKET_ACCESS_KEY_DUPLICATE     => 'shop access key duplicate.',
        self::SHOP_MARKET_SECRET_KEY_DUPLICATE     => 'shop secret key duplicate.',
        self::SHOP_ID_DUPLICATE                    => 'shop id duplicate.',
        self::USER_SELLER_REF_CODE_INVALID         => 'seller ref code invalid.',
        self::USER_RECHARGE_NOT_ENOUGH             => 'user recharge not enough.',
        self::USERNAME_DUPLICATE                   => 'username duplicate.',
        self::USER_CREATE_PARENT_NOT_FOUND         => 'user need a parent.',
        self::USER_CRATE_PARENT_ACC_TYPE_ERROR     => 'user parent account type error.',
        self::USER_ROLE_CODE_DUPLICATE             => 'role code duplicate.',
        self::PRODUCT_FAVORITE_NOT_ALLOW           => 'product favorite not allow.',
        self::PRODUCT_FAVORITE_DUPLICATE           => 'prdouct favorite duplicate.',
        self::PRODUCT_FAVORITE_USER_ERROR          => 'prdouct favorite user error.',
        self::PRODUCT_OPTION_NO_IMAGE              => 'product option no image.',
        self::PRODUCT_FAVORITE_SAVE_ERROR          => 'product favorite save error.',
        self::PRODUCT_MODIFY_LIMIT_SKU             => 'product modify limit sku.',
        self::PRODUCT_MODIFY_LIMIT_OPTION_NAME     => 'product modify limit option name.',
        self::PRODUCT_MODIFY_LIMIT_MARKET_CATEGORY => 'product modify limit market category',
        self::PRODUCT_SEND_PROCESSING              => 'prodouct send processing.',
        self::SECURITY_USER_NOT_ENABLE             => '탈퇴한회원으로 로그인이 불가합니다.',
        self::SHOP_SK11ST_CHECK_OUT_ADDRESS_ERROR  => 'SHOP_SK11ST_CHECK_OUT_ADDRESS_ERROR',
        self::SHOP_SK11ST_CHECK_OUT_COST_ERROR     => 'SHOP_SK11ST_CHECK_OUT_COST_ERROR',
        self::SHOP_SK11ST_IS_NOT_OVERSEAS          => '해외용 점포인지 확인하세요.',
        self::QNA_COUPANG_REQUEST_ANSWER           => '확인 처리 불가한 문의입니다.',
        self::ORDER_TAOBAO_WORLD_PRODUCT_NOT_FOUND => 'ORDER_TAOBAO_WORLD_PRODUCT_NOT_FOUND',
    ];
}