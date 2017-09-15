project_info:

---

title:E-Service

todos:
- 企业管理部分:
  - 企业首页强行添加的文字功能实现;
  - 小部分修改完善;

- 在线客服部分:
  - 智能客服部分整合进workerman框架;
  - 人工客服部分，客服列表数据结构改进;

- 其他:
  - 适配新的结构、命名、代码。比如调用action.php里的case注意更改后的名字，比如每个文件里的require、css、js引用规范……;
  - 现有文件整理，如css js images里无用删除;
  - ? 使用laravel替代workerman;
  - ? 项目转向laravel框架;
  - ? 寻找coreseek替代提高搜索性能;
  - ? 支持/转向linux，方便未来部署示例;

- 测试;

done:
- 智能客服记录输入的无法回答的问题，关键词处理;
- 智能客服点击问题后需要在case-question表中记录;
- 大量文件、代码、命名、结构整理;
- 企业统一使用enterprise名称;
- case-question表用来 企业管理-服务记录 查询;

---

database_info:

---

title:e-service

version:1.1.0

build_time:2017年9月6日00点31分

todos:
- 现有数据整理;
- 更详细真实的示例数据;

done:
- 新增关键词表访问次数;
- 关键词表的WContent唯一;
- 新增question-word表，绑定关键词和问题;