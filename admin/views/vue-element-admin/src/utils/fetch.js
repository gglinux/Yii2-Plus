import axios from 'axios';
import { Message } from 'element-ui';
import store from '../store';
// import router from '../router';

// 创建axios实例
const service = axios.create({
  baseURL: process.env.BASE_API, // api的base_url
  timeout: 5000,                  // 请求超时时间
  // //config里面有这个transformRquest，这个选项会在发送参数前进行处理。
  // //这时候我们通过Qs.stringify转换为表单查询参数
  // transformRequest: [function (data) {
  //   data = Qs.stringify(data);
  //   return data;
  // }],
  // //设置Content-Type
  // headers:{'Content-Type':'application/x-www-form-urlencoded'}
});

// request拦截器
service.interceptors.request.use(config => {
  // Do something before request is sent
  if (store.getters.token) {
    config.headers['X-Token'] = store.getters.token; // 让每个请求携带token--['X-Token']为自定义key 请根据实际情况自行修改
  }
  return config;
}, error => {
  // Do something with request error
  console.log(error); // for debug
  Promise.reject(error);
})

// respone拦截器
service.interceptors.response.use(
  response => response
  /**
  * 下面的注释为通过response自定义code来标示请求状态，当code返回如下情况为权限有问题，登出并返回到登录页
  * 如通过xmlhttprequest 状态码标识 逻辑可写在下面error中
  */
  // const code = response.data.code;
  // // 50014:Token 过期了 50012:其他客户端登录了 50008:非法的token
  // if (code === 50008 || code === 50014 || code === 50012) {
  //   Message({
  //     message: res.message,
  //     type: 'error',
  //     duration: 5 * 1000
  //   });
  //   // 登出
  //   store.dispatch('FedLogOut').then(() => {
  //     router.push({ path: '/login' })
  //   });
  // } else {
  //   return response
  // }
  ,
  error => {
    console.log('err' + error);// for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    });
    return Promise.reject(error);
  }
)

export default service;
