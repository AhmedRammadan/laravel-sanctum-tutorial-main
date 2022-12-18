import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/material.dart';

class NextScreen extends StatelessWidget {
  var message;
  NextScreen({required this.message, super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(),
      body: Center(child: Text("NextScreen\n ${message.data}")),
    );
  }
}
