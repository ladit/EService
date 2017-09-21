# E-Service

An online customer service system with knowledge base base on php.

---

# install

1. use composer to install workerman/workerman-for-win, workerman/gateway-worker-for-win, fukuball/jieba-php.

```
cd EService
composer install
```

2. install coreseek-4.1-win32 in EService\vendor.

3. create a datebase named "e-service" in mySQL and import \e-service_1.1.0.sql to "e-service".

---

# run

1. run bin\coreseek_service.bat to run coreseek to support search question.

2. run bin\workerman_service.bat to run workerman to support live chat.

---

# to-do

- 企业管理部分:
  - 企业部分功能实现;
  - 小部分修改完善;

- 在线客服部分:
  - 智能客服部分整合进 workerman 框架;

- 其他:
  - ? 项目转向 laravel 框架;
  - ? 使用 laravel 替代 workerman;
  - ? 寻找 coreseek 替代提高搜索性能;
  - ? 支持/转向 linux;

- 数据库部分
  - 现有数据整理;
  - 更详细真实的示例数据;

- 更详细高负载测试;
