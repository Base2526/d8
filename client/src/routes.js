// import { Pizza, EditPizza, Toppings, AnotherRoute } from "./Components/index";
import React, { Component } from 'react';
import { Link } from "react-router-dom";

import RideSelect from './Components/RideSelect/RideSelect';
import DepositPage from './Components/Members/DepositPage';
import WithdrawPage from './Components/Members/WithdrawPage';
import ContactUsPage from './Components/Members/ContactUsPage';
import AddBankPage from './Components/Members/AddBankPage';
import LoginPage from './Components/LoginPage/LoginPage';

import AffiliatePage from './Components/Members/AffiliatePage'
import StatementPage from './Components/Members/StatementPage'
import RequestAllPage from './Components/Members/RequestAllPage'

import LotteryListPage from './Components/Lottery/LotteryListPage';
import GovernmentPage from './Components/Lottery/GovernmentPage';

import YeekeeListPage from './Components/Lottery/YeekeeListPage';
import YeekeePage from './Components/Lottery/YeekeePage';

import ProfilePage from './Components/ProfilePage/ProfilePage';
import ForgetPasswordPage from './Components/ForgetPasswordPage/ForgetPasswordPage';
import RegisterPage from './Components/RegisterPage/RegisterPage';

const toppings = [
  { id: "1", name: "Cheese" },
  { id: "2", name: "Pepperoni" },
  { id: "3", name: "Ham" },
  { id: "4", name: "Pineapple" }
];

const pizza = [
  { id: "1", name: "Pepperoni", toppings: [toppings[0], toppings[1]] },
  { id: "2", name: "Cheese", toppings: [toppings[0]] },
  { id: "3", name: "Ham and Pineapple", toppings: [...toppings.slice(2)] }
];

const Pizza = () => (
  <div>
    <h1 className="bold text-6xl">List Of Pizza</h1>
    <ul>
      {pizza.map(({ id, name }) => (
        <li className="underline text-blue-500" key={id}>
          <Link to={`pizza/${id}`}>{name}</Link>
        </li>
      ))}
    </ul>
  </div>
);

const EditPizza = ({
  match: {
    params: { pizzaId }
  }
}) => (
  <div>
    <h1 className="bold text-6xl">
      Edit Pizza: {pizza.find(({ id }) => id === pizzaId).name}
    </h1>
    <Link className="underline text-blue-500" to="/pizza/1/toppings">
      View Toppings
    </Link>
  </div>
);

const Toppings = ({
  match: {
    params: { pizzaId }
  }
}) => (
  <div>
    <h1 className="bold text-6xl">
      Toppings for {pizza.find(({ id }) => id === pizzaId).name}
    </h1>
    <ul>
      {pizza
        .find(({ id }) => id === pizzaId)
        .toppings.map(({ id, name }) => (
          <li key={id}>{name}</li>
        ))}
    </ul>
  </div>
);

const AnotherRoute = () => <h1>Another One</h1>;

export default [
//   { path: "/", name: "Pizza", Component: Pizza },
    { path: "/pizza/:pizzaId", name: "Edit Pizza", Component: EditPizza },
    {
        path: "/pizza/:pizzaId/toppings",
        name: "Pizza Toppings",
        Component: Toppings
    },
    { path: "/another", name: "Another", Component: AnotherRoute },
    { path: "/one", name: "One", Component: AnotherRoute },

    { path: "/", name: "หน้าหลัก", Component: RideSelect },
    { path: "/login", name: "เข้าสู่ระบบ", Component: LoginPage },
    { path: "/register", name: "สมัครสมาชิก", Component: RegisterPage },
    { path: "/forget-password", name: "ลืมรหัสผ่าน", Component: ForgetPasswordPage },
    { path: "/profile-page", name: "โปรไฟล์", Component: ProfilePage },
    { path: "/affiliate-page", name: "แนะนำเพือน", Component: AffiliatePage },
    { path: "/statement", name: "รายงานการเงิน", Component: StatementPage },
    { path: "/request-all", name: "สถานะ ฝากเงิน", Component: RequestAllPage },

    { path: "/lottery-list", name: "แทงหวย", Component: LotteryListPage },
    { path: "/lottery-list/government", name: "หวยรัฐบาลไทย", Component: GovernmentPage },
    { path: "/lottery-list/yeekee-list", name: "จับยี่กี VIP", Component: YeekeeListPage },
    { path: "/lottery-list/yeekee-list/:id", name: "Yeekee", Component: YeekeePage },

    { path: "/deposit", name: "ฝากเงิน", Component: DepositPage },
    { path: "/withdraw", name: "ถอนเงิน", Component: WithdrawPage },
    { path: "/add-bank", name: "เพิ่มบัญชีธนาคาร", Component: AddBankPage },
    { path: "/contact-us", name: "ติดต่อเรา", Component: ContactUsPage}
];
