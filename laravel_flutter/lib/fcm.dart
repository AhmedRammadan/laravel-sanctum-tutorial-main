import 'dart:async';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:laravel_flutter/main.dart';
import 'firebase_options.dart';
import 'local_notification_app.dart';
import 'next_screen.dart';

class FCM {
  late FirebaseMessaging messaging;

  Future<void> init() async {
    messaging = FirebaseMessaging.instance;
    await messaging.setAutoInitEnabled(true);
    print("token ${await getdeviseToken()}");
    setupInteractedMessage();
    messaging.subscribeToTopic("user");
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
  }

  Future<String?> getdeviseToken() async => await messaging.getToken();

  Future<void> setSubscribeToTopic({String topic = "user"}) async =>
      await messaging.subscribeToTopic(topic);

  Future<void> _firebaseMessagingBackgroundHandler(
      RemoteMessage message) async {
    // If you're going to use other Firebase services in the background, such as Firestore,
    // make sure you call `initializeApp` before using other Firebase services.
    await Firebase.initializeApp(
      options: DefaultFirebaseOptions.currentPlatform,
    );

    print("Handling a background message: ${message.messageId}");
  }

  void requestPermission() async {
    NotificationSettings settings = await messaging.getNotificationSettings();
    if (settings.authorizationStatus != AuthorizationStatus.authorized) {
      settings = await messaging.requestPermission(
        alert: true,
        announcement: true,
        badge: true,
        carPlay: true,
        criticalAlert: true,
        provisional: true,
        sound: true,
      );
    }
    print('User granted permission: ${settings.authorizationStatus}');
  }

  // It is assumed that all messages contain a data field with the key 'type'
  Future<void> setupInteractedMessage() async {
    requestPermission();
    await messaging.setAutoInitEnabled(true);
    // Get any messages which caused the application to open from
    // a terminated state.

    RemoteMessage? initialMessage = await messaging.getInitialMessage();
    // If the message also contains a data property with a "type" of "chat",
    // navigate to a chat screen
    if (initialMessage != null) {
      _handleMessage(initialMessage);
    }

    // Also handle any interaction when the app is in the background via a
    // Stream listener
    FirebaseMessaging.onMessageOpenedApp.listen(_handleMessage);
    FirebaseMessaging.onMessage.listen((_handleMessage));
  }

  void _handleMessage(RemoteMessage message) {
    LocalNotificationApp.onDidReceiveLocalNotification(
        111111, "title", " body", message);

    if (message.data['type'] == 'chat') {
      // Navigator.push(
      //     GlobalVariable.navState.currentState!.context,
      //     MaterialPageRoute(
      //         builder: ((context) => NextScreen(message: message))));
    }
  }

 
}
